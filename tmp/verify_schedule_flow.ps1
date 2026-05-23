$ErrorActionPreference = 'Stop'
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

$loginPage = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/login' -WebSession $session
$tokenMatch = [regex]::Match($loginPage.Content, 'name="_token" value="([^"]+)"')
if (-not $tokenMatch.Success) { throw 'CSRF token not found on login page.' }

$loginBody = @{
    _token   = $tokenMatch.Groups[1].Value
    email    = 'admin@example.com'
    password = 'admin123'
    remember = 'on'
}

$loginResponse = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/login' -Method Post -Body $loginBody -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue
Write-Output "LOGIN_STATUS=$($loginResponse.StatusCode)"
if ($loginResponse.Headers.Location) {
    Write-Output "LOGIN_LOCATION=$($loginResponse.Headers.Location)"
}

$createPage = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/schedules/create' -WebSession $session
Write-Output "CREATE_STATUS=$($createPage.StatusCode)"
Write-Output ("HAS_SAVE_BUTTON=" + ($createPage.Content -match 'Save Schedule'))

$trainBlock = [regex]::Match($createPage.Content, '<select[^>]*id="train_id"[\s\S]*?</select>')
$routeBlock = [regex]::Match($createPage.Content, '<select[^>]*id="route_id"[\s\S]*?</select>')

if (-not $trainBlock.Success) { throw 'train select not found' }
if (-not $routeBlock.Success) { throw 'route select not found' }

$trainIdMatch = [regex]::Match($trainBlock.Value, '<option value="(\d+)"')
$routeIdMatch = [regex]::Match($routeBlock.Value, '<option value="(\d+)"')

if (-not $trainIdMatch.Success) { throw 'no train option found' }
if (-not $routeIdMatch.Success) { throw 'no route option found' }

$trainId = $trainIdMatch.Groups[1].Value
$routeId = $routeIdMatch.Groups[1].Value

Write-Output "TRAIN_ID=$trainId"
Write-Output "ROUTE_ID=$routeId"

$postPage = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/schedules' -WebSession $session
$postTokenMatch = [regex]::Match($postPage.Content, 'name="_token" value="([^"]+)"')
if (-not $postTokenMatch.Success) { throw 'CSRF token not found on schedules page.' }

$storeBody = @{
    _token           = $postTokenMatch.Groups[1].Value
    train_id         = $trainId
    route_id         = $routeId
    departure_time   = '08:00'
    arrival_time     = '09:30'
    fare             = '120.00'
    available_seats  = '40'
}

$storeResponse = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/schedules' -Method Post -Body $storeBody -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue
Write-Output "STORE_STATUS=$($storeResponse.StatusCode)"
if ($storeResponse.Headers.Location) {
    Write-Output "STORE_LOCATION=$($storeResponse.Headers.Location)"
}

$schedulesAfter = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/schedules' -WebSession $session
Write-Output ("CREATED_MESSAGE_PRESENT=" + ($schedulesAfter.Content -match 'Schedule created successfully'))
Write-Output ("NEW_ROW_PRESENT=" + ($schedulesAfter.Content -match '08:00'))
