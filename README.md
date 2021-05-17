## DecemberLabs Accounts and Transactions test

This proyect was made following the instructions given by mail

## Architecture
Models (with migrations, factories and seeders):
- Account
- Currency
- HouseAccountsRegistry (stores the ids of the "bank" accounts, where the transactions commission amount are added).
- Transaction
- User

![ERD](https://i.ibb.co/Lxc6Rbh/der-accounts.png)

Seeders:
- Currency: creates three currencies registry (EUR, USD and UYU).
- HouseAccountsRegistry: creates one account per currency for the "bank".
- User: creates three users, one with one Account, one with two and one with three.
- Transaction: creates a random quantity (1 to 16) of transactions for all accounts with balance greater than zero.

*Note: this is not what seeders are meant for, I did it to make it easier for you to test it*

Policies:
- Account: view method (determines if the user can see de account only if it owns it).

Controllers:
- Login: It handles API authentication, for test convenience, it just returns a freshly created token if the credentials are correct.
- Account: Provides index (list all accounts from the auth user) and show (show a specifi account from the auth user) methods.
- Transaction: methods to make transactions and list all user transactions (or given account transactions).

Helpers:
- CurrencyConverter: Determines if the amount of transaction must be converted and converts it if necessary, it comes with transaction_commision.php config file, where you can change the current commission percentage. *NOTE: This class is registered as a singleton in AppServiceProvided*
- Fixerio: Gets and store in cache the current exchange rate data for all available currencies. It uses fixerio.php config file, where you can set the API key, if the subscription plan is premium and the caching time, by default it comes set to the free plan and 60 minutes of caching storage because that plan update the data hourly, so it´s pointless to make more requests.

Resources:
- I made resources classes for Account and Transaction.

Collections:
- For previously named resources.

# API endpoints #
## Login ##
Get user API token.
<h3>URL</h3>
    /api/v1/login
<h3>URL</h3>
    POST
<h3>URL</h3>
    required: email
    required: password
<h3>URL</h3>
<h3>URL</h3>
    {
        "token": "8|f9pDfEuxeeXHWLBj7YazEaYVw73WICqQBj34yenJ",
        "message": "Success, please write down the token"
    }
<h3>URL</h3>
    {
        "message": "Unauthorized"
    }
## Show User Accounts ##
Get all the accounts from the authenticated user.
###URL: ###
    /api/v1/accounts
###Method: ###
    GET
###Success Response (CODE 200): ###
####Authenticated ####
    {
        "data": [
            {
                "id": 7,
                "user_id": 4,
                "user": "Antwan O'Connell",
                "currency_id": 3,
                "currency": "UYU",
                "balance": "45864.9446"
            },
            {
                "id": 8,
                "user_id": 4,
                "user": "Antwan O'Connell",
                "currency_id": 3,
                "currency": "UYU",
                "balance": "4430.4049"
            },
            {
                "id": 9,
                "user_id": 4,
                "user": "Antwan O'Connell",
                "currency_id": 1,
                "currency": "EUR",
                "balance": "19154.1308"
            }
        ]
    }
###Error Response: ###
####Not authenticated (CODE 401) ####
    {
        "message": "Unauthenticated"
    }
## Show Account Details ##
Get all the accounts from the authenticated user.
###URL: ###
    /api/v1/accounts/{id}
###Method: ###
    GET
###Success Response (CODE 200): ###
####Authenticated ####
    {
        "data": {
            "id": 7,
            "user_id": 4,
            "user": "Lyric Rogahn I",
            "currency_id": 1,
            "currency": "EUR",
            "balance": "23405.4171"
        }
    }
###Error Response: ###
####Not authenticated (CODE 401) ####
    {
        "message": "Unauthenticated"
    }
####Try to access to another user account (CODE 403) ####
    {
        "message": "This action is unauthorized."
    }
## Show Transactions Details ##
Get all the transactions from accounts of the authenticated user.
###URL: ###
    /api/transactions
###Method: ###
    GET
###URL Params: ###
    optional: From=[Date]
    optional: To=[Date]
    optional: SourceAccountID=[integer]
###Success Response (CODE 200): ###
####Authenticated ####
    {
        "data": [
            {
                "id": 7,
                "origin_account_id": 7,
                "origin_account": {
                    "id": 7,
                    "user_id": 4,
                    "user": "Lyric Rogahn I",
                    "currency_id": 1,
                    "currency": "EUR",
                    "balance": "23405.4171"
                },
                "destination_account_id": 6,
                "destination_account": {
                    "id": 6,
                    "user_id": 3,
                    "user": "Lucy Stokes",
                    "currency_id": 2,
                    "currency": "USD"
                },
                "amount": "13117.9197",
                "description": "Qui minima sit praesentium. Quidem laboriosam vero sint ipsum sapiente amet debitis. Magni neque doloribus reprehenderit est quis eaque.",
                "converted": "15945.2249",
                "complete": "2021-05-17 17:18:16"
            },
            ...
        ]
    }
###Error Response: ###
####Not authenticated (CODE 401) ####
    {
        "message": "Unauthenticated"
    }
####Try to access to another user account (CODE 403) ####
    {
        "message": "This action is unauthorized."
    }
## Make Transaction ##
Make a transaction.
###URL: ###
    /api/transfer
###Method: ###
    POST
###URL Params: ###
    required: body=[{
        accountFrom=[integer]
        accountTo=[integer]
        amoun=[float]
        date=[Timestamp]
        description=[string]
    }]
###Success Response (CODE 200): ###
####Authenticated ####
    {
        "data": {
            "id": 14,
            "origin_account_id": 8,
            "origin_account": {
                "id": 8,
                "user_id": 4,
                "user": "Lyric Rogahn I",
                "currency_id": 1,
                "currency": "EUR",
                "balance": "32996.4612"
            },
            "destination_account_id": 4,
            "destination_account": {
                "id": 4,
                "user_id": 2,
                "user": "Claudia Orn",
                "currency_id": 3,
                "currency": "UYU"
            },
            "amount": 1.12,
            "description": "Test transaction",
            "converted": 60.163554080000004,
            "complete": "2021-05-17T17:58:30.342324Z"
        }
    }
###Error Response: ###
####Not authenticated (CODE 401) ####
    {
        "message": "Unauthenticated"
    }
####Try to send from another user account (CODE 403) ####
    {
        "message": "This action is unauthorized."
    }
<br>

[Here](https://www.getpostman.com/collections/1260951c8b7e21d6739a) you can download a postman collection.

## Possible improvements
- Add feature and unit tests
- Define a cronjob that updates the cache every 'x' amount of time, where 'x' is the Fixerio update time.
- Parameterizable commission: a column could be added in the accounts table that indicates the commission for its operations, thus being able to have a finer control over the commissions and allowing to offer different plans to different users.
- Define minimum transaction amount, this can be added in the currencies table, allowing different minimum amounts for each currency, in turn you can define a listener so that when one changes, the increase (or decrease) is affected in the others currencies.
- Use redis for cache.
- Defining versioning in the API routes, to follow the instructions of the requested routes I did not do it, but you could add "vX" as a prefix in the routes, where X is the version number, allowing greater flexibility when updating the endpoints.