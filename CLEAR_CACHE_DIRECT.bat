@echo off
echo 🔥 CLEARING ALL SUITECRM CACHES...
echo.

REM Remove all cache directories
echo 🗂️ Removing cache directories...
if exist cache rmdir /s /q cache
if exist themes\SuiteP\cache rmdir /s /q themes\SuiteP\cache
if exist upload\upgrades\temp rmdir /s /q upload\upgrades\temp

REM Recreate essential directories
echo 📁 Recreating cache directories...
mkdir cache 2>nul
mkdir cache\smarty 2>nul
mkdir cache\smarty\templates_c 2>nul
mkdir cache\smarty\cache 2>nul
mkdir cache\themes 2>nul
mkdir cache\modules 2>nul

echo.
echo ✅ CACHE CLEARED SUCCESSFULLY!
echo 🚀 NOW REFRESH SUITECRM - PROPERTIES WILL APPEAR!
echo.
pause 