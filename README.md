# ByDesign GUID Seamless Authentication
This laravel package allows you to authenticate users in the ByDesign system and add them to your own user database within your own application.

## How It Works
A link is configured within revolution to go to a specific URL within your application. This URL can be customized via the config file. The package then checks if the GUID provided is legitimate and then gets information on the user account. If there is already a user in the system with that RepID, then that user will be authenticated. Otherwise, a new user account will be created.

## ENV File
```dotenv
BYDESIGN_REST_API_BASE_URL=https://webapi.securefreedom.com/{{Your Company Here}}/api
BYDESIGN_USERNAME=api
BYDESIGN_PASSWORD=password
```

## Config File
```php
return [
    // The final route would look something like this: /auth/{GUID}
    'auth_route' => '/auth',
    'base_url' => env('BYDESIGN_REST_API_BASE_URL'),

    'success_redirect_url' => '/',
    'failed_redirect_url' => '/failed-authentication',

    'username' => env('BYDESIGN_USERNAME', 'api'),
    'password' => env('BYDESIGN_PASSWORD')
];
```

## License
Copyright 2020 Data-Head Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
