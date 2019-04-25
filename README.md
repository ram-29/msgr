<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template (MOD)</h1>
    <br>
</p>

Yii 2 Advanced Project Template on steriods. Configured alongside with user-interface, widgets, tools and libraries to super charge your development.

Pre-Installed Packages
-------------------

```
UI
    dmstr
        yii2-adminlte-asset

WIDGETS
    kartik
        yii2-grid
        yii2-widget-select2
        yii2-widget-datepicker
        yii2-number
        yii2-export
        yii2-editable
        yii2-mpdf
    2amigos
        yii2-chartjs-widget
        yii2-gallery-widget
    axelpal
        yii2-attachments
    dominus77
        yii2-sweetalert2-widget

MANAGEMENT/TOOLS
    dektrium
        yii2-user
        yii2-rbac
    wbraganca
        yii2-dynamicform
    c006
        yii2-migration-utility
    geekcom
        phpjasper
    himiklab
        yii2-easy-thumbnail-image-helper

LIBRARIES/SCRIPTS
    madand
        yii2-momentjs
    bdelespierre
        underscore
    bower-asset
        lodash
    ramsey
        uuid
    openlss
        lib-array2xml
```

Afterwards just run these commands on your terminal.

```
1. php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
2. php yii migrate/up --migrationPath=@yii/rbac/migrations
3. php yii migrate/up --migrationPath=@vendor/axelpal/yii2-attachments/migrations
```
