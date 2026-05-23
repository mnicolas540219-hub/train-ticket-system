$ErrorActionPreference = 'Stop'
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

$loginPage = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/login' -WebSession $session
$tokenMatch = [regex]::Match($loginPage.Content, 'name="_token" value="([^"]+)"')
if (-not $tokenMatch.Success) { throw 'CSRF token not found on login page.' }

$body = @{
    _token   = $tokenMatch.Groups[1].Value
    email    = 'admin@example.com'
    password = 'admin123'
    remember  = 'on'
}

$loginResponse = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/login' -Method Post -Body $body -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue
Write-Output "LOGIN_STATUS=$($loginResponse.StatusCode)"
if ($loginResponse.Headers.Location) {
    Write-Output "LOGIN_LOCATION=$($loginResponse.Headers.Location)"
}

$schedulesPage = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/schedules' -WebSession $session
Write-Output "SCHEDULES_STATUS=$($schedulesPage.StatusCode)"
Write-Output ("HAS_ADD_BUTTON=" + ($schedulesPage.Content -match 'Add Schedule'))
Write-Output ("HAS_CREATE_LINK=" + ($schedulesPage.Content -match 'schedules/create'))
