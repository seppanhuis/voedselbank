@echo off
setlocal enabledelayedexpansion

:: Commit message ophalen
set message=%*

if "%message%"=="" (
    echo Geef een commit message mee!
    exit /b
)

:: Git commands
git add .
git commit -m "%message%"

echo.
echo Beschikbare branches (lokaal + remote):
echo.

set count=0

for /f "delims=" %%b in ('git branch -a') do (
    set line=%%b

    :: Huidige branch sterretje verwijderen
    set line=!line:* =!

    :: "remotes/origin/" verwijderen
    set line=!line:remotes/origin/=!

    :: HEAD regels skippen
    echo !line! | findstr "HEAD" >nul
    if errorlevel 1 (
        set /a count+=1
        set branch[!count!]=!line!
        echo !count!. !line!
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
echo Push naar !selected!...
git push origin !selected!

endlocal
