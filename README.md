# PAYMILL Unite Marketplace Example

To demonstrate you the easy implementation and workflow of PAYMILL Unite we created two example scenarios, which you can go through to understand how our product works:

* Connect your marketplace with your merchants with PAYMILL Connect
* Do transactions with the granted permissions and raise fees

## General information

The following lines give you a short introduction into PAYMILL Unite and PAYMILL Connect:

### PAYMILL Unite

PAYMILL Unite is a solution designed for market-place-format-businesses.
It allows you to connect your account with any other PAYMILL account to make transactions in its name.
Of course it's also possible to get access to all other API functionalities like refunds, subscriptions, clients etc.
You as marketplace are an app provider which the merchant grant access to their data. PAYMILL Unite gives you an overview over the connected merchants and your permissions and helps you with the fee collection. As marketplace you have the possibility to raise fees for the transactions you've done for your merchants to get your portion out of the marketplace agreement.

More about PAYMILL Unite: https://www.paymill.com/en-gb/product/unite/

### PAYMILL Connect

PAYMILL Connect is the technology which allows you to connect your merchants easily and secureley with your account with just a couple of steps:

* Add the PAYMILL Connect button which brings your merchant to our connect page.
* Your merchant accepts your connect request via OAuth2 (after signing up for free or login to their PAYMILL account).
* We send them back. Now you have your own key to access your merchants PAYMILL account.

More about PAYMILL Connect: https://www.paymill.com/en-gb/documentation-3/add-ons/connect/

## Explanation of the examples

To get the examples running you need a web server like Apache or NGINX with PHP >= 5.3.
Copy the content of this package into your document root.

E.g. _http://test.local/paymill-unite-example_

We will use this example URL as base for the following explanations.

**To get prepared you need to register at least one PAYMILL account which acts as app.**
See [Registering an application](https://github.com/paymill/paymill-unite-example#registering-an-application) for more information.

Please enter all your data into the configuration file:
_paymill-unite-example/library/unite.php_

This example does not need a database connection to store the received connect data.
But it uses a CSV file: _paymill-unite-example/system/merchant.csv_

Make sure that this file is writable:

```
chmod 777 paymill-unite-example/system/merchant.csv
```

### Connect your marketplace with your merchants with PAYMILL Connect

Open the first example which representates your initial connect page with the PAYMILL Connect button:

_http://test.local/paymill-unite-example/merchant_

As you can see in the short description below, the button will direct your merchant to our connect page.
The URL contains some important parameters:

* _client_id_: Your app id
* _scope_: Your requested permissions
* _redirect_uri_ (optional): Your redirect URI to receive your access key after the authorization.

See [Requesting an authorization code via OAuth2](https://github.com/paymill/paymill-unite-example#requesting-an-authorization-code) for more information.

The PAYMILL Connect page shows your app name and logo (if upload) with a login form below.
If your merchant already has a PAYMILL account he can login, if not he can register for free to access the authorization page. If you register a new merchant account, don't forget to click on the link in the activation mail.

After he authorized your request by clicking the "Authorize" button he will be redirect to your specified redirect URI. In our example we defined _http://test.local/paymill-unite-example/system/authMerchant.php_.
The PHP script _authMerchant.php_ stores the received connection data into the CSV file (paymill-unite-example/system/merchant.csv), for the later use for doing transactions.

After storing the data, the script redirects the merchant to a success page _http://test.local/paymill-unite-example/merchant/final.php_:

**Authorization was successful!**

### Do transactions with the granted permissions and raise fees

Since the authorization was done, you have your access key for the connected merchant in the CSV file (paymill-unite-example/system/merchant.csv).
Feel free to store your access key for your usage in a database or where ever you want as long it is save.

To test the transaction example go to:
_http://test.local/paymill-unite-example_

Here you see an example marketplace shopping cart with a total amount which must be paid.
The shopping cart contains the product of your merchant.
Below the shopping cart you find the payment form. The payment data is pre filled to make the test easier.
The fields which are deactived should be hidden, they are visible to make it better understandable for you.
As you can see there is also a "fee" field. This field allows you to send an (included) fee for this transaction.
This fee will be collected for each transaction and transferred to your account by us (on a weekly basis).

When you look at the source code of this file (paymill-unite-example/index.php) you find the JavaScrip variable **PAYMILL_PUBLIC_KEY**, which contains the public key of your merchant (you received it with PAYMILL Connect).
But to do the transaction itself, which is done in _paymill-unite-example/paymill_test.php_ you need your personal access key **$access_token**, which you also received with PAYMILL Connect.

For the fee transfer your connected merchant need to be a client of your app with a valid payment object.
this payment object must also be past to the transaction create function (as you can see in paymill-unite-example/paymill_test.php).

The rest is the same procedure as explained in our [brief instructions](https://www.paymill.com/en-gb/documentation-3/introduction/brief-instructions/).

We hope that our example helps you to integrate PAYMILL Unite.

For more detailed information please study the following paragraphs.

## How to use our marketplace example

First thing you have to do is to register a free PAYMILL account at https://www.paymill.com.

### Registering an application

Navigate to our Cockpit and open the account preferences page. In the “Application” tab you can register a new application. You can register two applications: one for live mode and one for test mode. The difference between live and test applications are:

* For test applications you will only receive test api keys for connected merchants
* For live applications you will only receive live api keys for connected merchants
* Live api keys for connected merchants are not functional as long as the merchant hasn't completed the activation (see Creating the access token and Webhooks for applications).

### OAuth2 Workflow

#### Requesting an authorization code

To request an authorization code for another merchants account, you’ll have to redirect that merchant to the authorization page on our servers.
The target url for this redirect https://connect.paymill.com/authorize and you need to append a few query parameters to the request:

* _client_id_ (required): The application id given to you upon application registration.
* _response_type_ (required): Fixed string set to "code".
* _scope_ (required): A space-separated list of permissions you want to request.
* _redirect_uri_ (optional): If you need a different redirect URI (to that from the app settings).
* _custom_param_ (optional): If you want to pass additional values.

**Example:**

    https://connect.paymill.com/
    ?client_id=app_1d70acbf80c8c35ce83680715c06be0d15c06be0d
    &scope=transactions_rw%20refunds_rw
    &response_type=code

When the merchant is redirected back to your page, we'll append a few query parameters to the uri
you provided when registering your application, depending on the outcome of the request.

If the merchant authorizes your request, the parameter code, containing your authorization code,
is appended.

If an error occurrs or if the merchant denies authorization, the parameters error and error_des
cription are appended. error contains an error key, error_description a descriptive message for that error in english.

Success response:

    https://example.com/?code=16a892ebeb21eb286396a1962796af830cbaa3c4

Error response:

    https://example.com/?error=access_denied&error_description=The+user+denied+access+to+your+application

* _access_denied_: The user denied access to your application
* _invalid_request_: Invalid or missing response type
* _unsupported_response_type_: authorization code grant type not supported
* _invalid_scope_: An unsupported scope was requested

#### Privileges

There are 3 different privileges for each PAYMILL API endpoint:

* _Read_: read all objects from this api endpoint.
* _Write_: write objects to this api endoint, read and edit objects writen by yourself.
* _ReadWrite_: read all objects, write new objects, edit anything.

A privlege is specified by the name of an api endpoint, like transactions, followed by an underscore, followed by r (read), w (write), or rw (read and write).

**Example:** transactions_rw (read and write transactions), clients_r (read clients), refunds_w (create new refunds).
possible Endpoints: clients, offers, payments, preauthorizations, refunds, subscriptions, transactions, webhooks.

**Note:** We combine the r and w flag automatically. If you request privileges like transactions_r transactions_w then we will combine this into a single transactions_rw.

**Note:** Not all combinations of privileges do make sense. For example it is not useful to request refunds_w without transaction_w because you can not refund transactions without being able to write transactions. Which privileges are really needed completely depends on your use case. We also ask you to request only the really necessary privileges for your app. For example in the case of marketplaces it is enough to request the write privileges.

#### Creating the access token

If a merchant authorized your request you are handed an authorization code you can exchange for an access token. The access token as described on OAuth2 is a private api key to your merchants account. The api key is also equiped with the privileges you specified during the authorization request.

In order to create the access token, you have to call the access token endpoint at https://connect.paymill.com/token with a POST request. The request body must contain the request parameters using application/x-www-form-urlencoded format.

* _grant_type_ (required): string set to the fixed value "authorization_code"
* _code_ (required): the authorization code retrieved earlier
* _client_id_ (required): the application id you created when registering an application.
* _client_secret_ (required): the access key as show in your app settings. This is automatically the same value as your private api key / private test api key).

**Note:** An authorization code expires within 30 seconds. If you don't request an access token within the time span, the code is invalidated and you'll get an error response when requesting the access token.

A successful response contains a private api key to the merchants account plus his public api key and a refresh token. If the private api key is a live key, then the response also includes the currencies and payment methods supported by the merchant. The response is sent as application/json:

```json
{
    "access_token": "bfa0e34a3073dc7f06e26bffe74077a3",
    "token_type": "basic",
    "refresh_token": "07fda540e5283039683f6400651b5eaf",
    "public_key": "054222812442d41085001d40fbb31d0b",
    "merchant_id": "mer_1d70acbf80c8c35ce83680715c06be0d15c06be0d",
    "currencies": ["EUR", "GBP"],
    "methods": ["visa", "mastercard", "amex"]
}
```

* currencies may contain every 3-letter currency code supported by PAYMILL and specified by _ISO 4217_.
* methods may contain a combination of the following:
  * _visa_: Visa cards
  * _mastercard_: MasterCard cards
  * _amex_: American Express cards
  * _jcb_: JCB cards
  * _dinersclub_: DinersClub cards
  * _cup_: China UnionPay cards
  * _elv_: Direct debit (ELV, Germany only).

**Note:** If you request a live api key for a merchant account which can't process live transactions yet then the access token response won't contain a public key and the private key can't be used until the merchant completed the activation process. You can always request a new api key by using the refresh token. See Refreshing an access token for details. See Webhooks for applications for details on activation notifications.

If something went wrong, an error response is returned as follows:

```json
{
    "error": "invalid_grant",
    "error_description": "Token is no longer valid"
}
```


**Possible errors are ...**

* _invalid_request_: Request is invalid (no POST request).
* _unsupported_grant_type_: grant_type parameter not set to authorization_code.
* _invalid_grant_: Authorization code is invalid or expired.
* _invalid_scope_: The requested privileges are not a subset of the originally requested privileges.

#### Refreshing an access token

Whenever requesting an access token (either by providing an authorization code or refresh token) you are given a refresh token along side with the access token. This refresh token can be used to generate a new access token at any time.

**Note:** Generating a new api key with an refresh token invalidates the previously generated api key. There can only be one key per authorization at any time.

Trading a refresh token for an access token works similarly as creating an access token with an authorization code: you POST a few parameters to https://connect.paymill.com/token and receive an access token response as described in Creating the access token.

**Possible parameters contain ...**

* _grant_type_ (required): fixed string set to refresh_token.
* _refresh_token_ (required): the refresh token as given when the last access token was created.
* _scope_ (optional): optional set of privileges for the new access token. Must be a subset of the originally requested privileges. Set to the originally requested privileges if omitted.

The response is identical to the one described in Creating the access token.

#### Pre-filling activation information

When redirecting a merchant to our authorization page he may not have a PAYMILL account yet. You probably already have data on the merchant which is also required by our activation process. In order to streamline this process for your merchants, you can send this data along side with an authorization request by adding parameters to the request. If the merchant is then presented with the activation form it will be pre-filled with all data you were able to provide us with.

**Note:** The authorization request requires parameters to be passed within the query string. URL however are limited to around 2000 characters in some web browsers. In order to send in extensive activation data, we also allow the authorization request to be made as a POST request. You can then send in the activation data as application/x-www-form-urlencoded request body. Note however that all OAuth2 related parameters as described in Requesting an authorization code have to be put into the query string, no matter what request method you choose.

List of possible activation data (work in progress): [See PDF documentation](https://static.paymill.com/r/126f1f21ef19d23021f3a04c1e4ab73fccc7b82d/downloads/20130619-documentation_unite.pdf).

### API extensions for applications

#### Collecting application fees

If creating a transaction through a merchant's PAYMILL account, you can collect a fee for this transaction by adding the fee_amount and fee_payment parameter. The fee is specified in the same format as the transaction amount. The fee payment should represent a payment of the merchant which will be billed in order to collect the fee:

```
curl -XPOST https://api.paymill.com/v2/transactions
 -d amount=4200
 -d token=098f6bcd4621d373cade4e832627b4f6
 -d currency=EUR
 -d fee_amount=420
 -d fee_payment=pay_098f6bcd4621d373cade4e832627b4f6
 -u <ACCESSTOKEN>:
```

This request will charge an amount of 42.00€ through the merchants account. PAYMILL will collect the default disagio and transaction fee. Your additional fee is drawn from the remaining amount.

**Note:** A merchant will receive the full transaction amount upfront. The application fee is accumulated and collected on a weekly basis and then transferred to the application.

The transaction now also contains a list of fee objects:

```json
{
    "data": {
        "id": "tran_54645bcb98ba7acfe204",
        "amount": 4200,
        ...
        "fees": [
            {
                 "type": "application",
                 "application": "app_1d70acbf80c8c35ce83680715c06be0d15c06be0d",
                 "payment": "pay_098f6bcd4621d373cade4e832627b4f6",
                 "amount": 420
            }
        ]
    }
}
```

#### Sharing payment information

When accessing our API by using an API key from your merchant, all objects which are created are also only available in this merchant account.
This is not desirable for payment information as you probably wan't to store it on your own account and use it for transactions on your merchant's accounts.

In order to achieve this, you can use a payment objects and token with all merchants which are connected to your application. This is true for all API endpoints which accept a payment id or token as a POST parameter.

The general approach is as follows (for example with transactions):

1. You post a payment id as payment parameter, along with client, amount and currency to https://api.paymill.com/v2/transactions
2. We lookup the payment and client in the merchant account the used API key is valid for.
3. If the lookup fails, we look up the payment and client in the application's merchant account.
4. If this lookup fails to, we return the usual API error ("payment not found").
5. If either lookup succeeds, we use the found payment object.
6. If it's a payment object from the application's merchant account, the object is duplicated and stored with the merchant's account.
7. The transaction object is returned, containing the payment information.

The same process is true for the token parameter.

**Note:** As described in 6., a payment object which belongs to the application owner's account, is duplicated the first time it's used with another merchant. You therefore require the write privilege in order to share payment objects. You also need the read privilege in order to use a payment object from the merchant account.
So, if you intent on sharing payment information with your connected merchants, you should request read and write privileges to the payments endpoint.

### Webhooks for applications

There are three new webhook events available:

* _app.merchant.activated_: triggered as soon as a connected merchant completed the activation process. Live keys only become valid after this event.
* _app.merchant.deactivated_: triggered if a merchant, which has been activated previously gets deactivated again. This might be triggered together with _app.merchant.rejected_.
* _app.merchant.rejected_: triggered if a newly connected merchant is rejected during his activation process. Live keys will never become valid if this event occurs.

These webhooks can be registered to the application's account and are triggered for every merchant who connects to this application.

**Note:** app.merchant.activated and app.merchant.rejected are only triggered for merchant's which hadn't completed their activation when connecting to your application.

The webhook data contains the user_id which was already given to you upon the access token request. This id can be used to match a webhook call to an authorization / connect attempt.

**Example:**

```json
{
    "event":
    {
        "event_type": "merchant.activated",
        "event_resource": {
            "merchant": "mer_1d70acbf80c8c35ce83680715c06be0d15c06be0d"
        },
        "created_at": 1358027174
    }
}
```
