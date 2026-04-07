@echo off
setlocal enabledelayedexpansion

echo Remote branches ophalen...
git fetch --all

echo.
echo Lokale tracking branches aanmaken:
echo.

for /f "delims=" %%b in ('git branch -r') do (
    set line=%%b

    :: HEAD overslaan
    echo !line! | findstr "HEAD" >nul
    if errorlevel 1 (

        :: "origin/" verwijderen
        set branch=!line:origin/=!

        :: Check of branch al lokaal bestaat
        git show-ref --verify --quiet refs/heads/!branch!
        if errorlevel 1 (
            echo Maak lokale branch: !branch!
            git branch !branch! origin/!branch!
        ) else (
            echo Bestaat al: !branch!
        )
    )
)

echo.
echo Klaar! Alle remote branches zijn nu lokaal beschikbaar.
endlocal
