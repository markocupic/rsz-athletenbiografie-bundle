:: Run easy-coding-standard (ecs) via this batch file inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
:: src
vendor\bin\ecs check vendor/markocupic/rsz-athletenbiografie-bundle/src --fix --config vendor/markocupic/rsz-athletenbiografie-bundle/.ecs/config/default.php
:: tests
vendor\bin\ecs check vendor/markocupic/rsz-athletenbiografie-bundle/tests --fix --config vendor/markocupic/rsz-athletenbiografie-bundle/.ecs/config/default.php
::
cd vendor/markocupic/rsz-athletenbiografie-bundle/.ecs./batch/fix
