# tvSuperSelect_customlist
Новый функционал позволяет навесить свой процессор на получение значений для любого ТВ с типом tvSuperSelect. Теперь мы можем вывести абсолютно любые значения в этом ТВ, которые передадим через наш процессор. Предлагаю рассмотреть подробнее, как это делается и что это нам даст.

## при использовании в обычном TV
#### Connector URL
`/assets/custom/tvssconnector.php`
#### Процессор
`tvss/getresources`

## при использовании MIGx
#### Input TV type
`tvSuperSelect`
#### Configs
`{"allowBlank":"true","connectorUrl":"\/assets\/custom\/tvssconnector.php","processorAction":"tvss\/getresources"}`
