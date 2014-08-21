set projectPath=%~dp0
set netBeans=A:\NetBeansIDE\_Start.bat


rem NetBeansIDE needs the project path without a trailing backslash
if %projectPath:~-1%==\ set projectPath=%projectPath:~0,-1%

"%netBeans%" "%projectPath%"