@echo off
setlocal enabledelayedexpansion

:: Commit message ophalen
set message=%*

if "%message%"=="" (
    echo Geef een commit message mee!
    exit /b
)

:: Commit uitvoeren
git add .
git commit -m "%message%"

echo.
echo Beschikbare branches (lokaal + remote):
echo.

set count=0

for /f "delims=" %%b in ('git branch -a') do (
    set line=%%b

    :: Sterretje verwijderen
    set line=!line:* =!

    :: HEAD regels overslaan
    echo !line! | findstr "HEAD" >nul
    if errorlevel 1 (

        :: remotes/origin/ verwijderen
        set clean=!line:remotes/origin/=!

        set /a count+=1
        set branch[!count!]=!clean!
        echo !count!. !clean!
    )
)

echo.
set /p choice=Kies een branch nummer:

set selected=!branch[%choice%]!

if "!selected!"=="" (
    echo Ongeldige keuze!
    exit /b
)

echo.
echo Gekozen branch: !selected!

:: Check of branch lokaal bestaat
git show-ref --verify --quiet refs/heads/!selected!
if errorlevel 1 (
    echo Branch bestaat niet lokaal. Maak tracking branch...
    git checkout -b !selected! origin/!selected!
) else (
    echo Branch bestaat lokaal. Checkout...
    git checkout !selected!
)

echo.
echo Push naar !selected!...
git push origin !selected!

endlocal
