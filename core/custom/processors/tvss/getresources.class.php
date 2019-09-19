<?php
class customTvssComboGetResourcesProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $defaultSortField = 'menuindex';
    public $defaultSortDirection = 'ASC';

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if ($context_key = $this->getProperty('context_key')) {
            $c->where(array('context_key' => $context_key));
        }
        if ($resource_id = (int)$this->getProperty('resource_id', 0)) {
            $c->where(array('id:!=' => $resource_id));
        }
        if ($query = trim($this->getProperty('query'))) {
            $c->where(array(
                'id:=' => "{$query}",
                'OR:pagetitle:LIKE' => "%{$query}%",
                'OR:longtitle:LIKE' => "%{$query}%",
            ));
        }

        return $c;
    }

    /**
     * @param xPDOObject $obj
     *
     * @return array
     */
    public function prepareRow(xPDOObject $obj)
    {
        $array = $obj->toArray();
        $array['parents'] = array();
        $parents = $this->modx->getParentIds($array['id'], 4, array('context' => $array['context_key']));
        if ($parents[count($parents) - 1] == 0) {
            unset($parents[count($parents) - 1]);
        }
        if (!empty($parents) && is_array($parents)) {
            $q = $this->modx->newQuery($this->classKey, array('id:IN' => $parents));
            $q->select('id,pagetitle');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $key = array_search($row['id'], $parents);
                    if ($key !== false) {
                        $parents[$key] = $row['pagetitle'];
                    }
                }
            }
            $array['parents'] = array_reverse($parents);
        }
        unset($parents, $q, $row, $key);
        
        $data = array(
            'display' => "<div><small>({$array['id']})</small> <b>{$array['pagetitle']}</b></div>",
            'value' => $array['id'],
        );
        if (!empty($array['parents'])) {
            $data['display'] = '<div class="parents"><small>' . join(' / ', $array['parents']) . '</small></div>' . $data['display'];
        }

        return $data;
    }
}

return 'customTvssComboGetResourcesProcessor';