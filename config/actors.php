<?php

return [
    // Positions are stable identifiers, never alphabetical display indexes.
    'types' => [
        'UnNE' => [0=>'Ninguna',1=>'Cooperativa',2=>'Empresa del Estado',3=>'Empresario Individual',4=>'Empresa Individual de Responsabilidad Limitada',5=>'Fundación u Corporación con Fines Empresariales',6=>'Sociedad Anónima',7=>'Sociedad Colectiva Comercial',8=>'Sociedad de Hecho',9=>'Sociedad de Responsabilidad Limitada',10=>'Sociedad en Comandita',11=>'Sociedad por Acciones'],
        'UnSe' => [0=>'Ninguna',1=>'Comunidad',2=>'Empresa',3=>'Fiscal',4=>'Grupo',5=>'Individuo',6=>'Institución'],
        'UnSo' => [0=>'Ninguna',1=>'Bienes',2=>'Confiable',3=>'Convenio',4=>'Específico',5=>'Recursos',6=>'Servicios'],
        'UnOp' => [0=>'Ninguna',1=>'Asistente',2=>'Ayudante',3=>'Colaborador',4=>'Obrero',5=>'Profesional',6=>'Técnico',7=>'Trabajador'],
        'UnEl' => [0=>'Ninguna',1=>'Abuelo(a)',2=>'Amigo(a)',3=>'Conocido(a)',4=>'Esposo(a)',5=>'Hermano(a)',6=>'Hijo(a)',7=>'Madre',8=>'Mascota',9=>'Padre',10=>'Pariente',11=>'Primo(a)',12=>'Sobrino(a)',13=>'Tío(a)',14=>'Vecino(a)'],
    ],
    'links' => ['UnNE'=>['UnSe','UnSo','UnOp','UnEl'],'UnSe'=>['UnSo','UnOp','UnEl'],'UnSo'=>['UnOp','UnEl'],'UnOp'=>['UnEl'],'UnEl'=>[]],
    'permissions' => [
        'actors.view'=>'Consultar actores', 'actors.create'=>'Registrar actores', 'actors.edit'=>'Modificar actores',
        'actors.archive'=>'Archivar actores', 'actors.classify'=>'Asignar clasificación SyQuAc',
        'actors.link'=>'Administrar vínculos entre unidades', 'actors.interactions'=>'Determinar interacciones',
        'actors.authorize'=>'Autorizar participación', 'actors.access'=>'Autorizar acceso a la aplicación',
        'identities.view'=>'Consultar datos formales de identidades propias', 'identities.manage'=>'Administrar identidades propias',
        'access.manage'=>'Administrar roles y permisos del sistema',
    ],
];
