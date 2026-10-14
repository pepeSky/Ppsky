<?php
return [
    'synonym' => ['label' => 'Sinónimo de', 'inverse' => 'Sinónimo de', 'symmetric' => true],
    'antonym' => ['label' => 'Antónimo de', 'inverse' => 'Antónimo de', 'symmetric' => true],
    'related' => ['label' => 'Relacionado con', 'inverse' => 'Relacionado con', 'symmetric' => true],
    'broader' => ['label' => 'Más general que', 'inverse' => 'Más específico que', 'symmetric' => false],
    'contains' => ['label' => 'Contiene', 'inverse' => 'Forma parte de', 'symmetric' => false],
    'depends_on' => ['label' => 'Depende de', 'inverse' => 'Es dependencia de', 'symmetric' => false],
    'requires' => ['label' => 'Requiere', 'inverse' => 'Es requerido por', 'symmetric' => false],
];
