<h1 align="center"><img src="https://make.hmn.md/altis/Altis-logo.svg" width="89" alt="Altis" /> Consent</h1>

<p align="center">Enhanced user privacy and consent controls for <strong><a href="https://altis-dxp.com/">Altis</a></strong>.</p>

<p align="center"><a href="https://packagist.org/packages/altis/consent"><img alt="Packagist Version" src="https://img.shields.io/packagist/v/altis/consent.svg"></a></p>

## What is the Altis Consent module?

Altis Consent module provides a website administrator(you) with a centralized and consistent way of obtaining data privacy consent from their website's users/visitors. Data privacy consent refers to how user data is tracked, collected, or stored on a website. 

### Web Cookies
A website uses cookies to track user behaviour on the website. Web cookies can be of three types: session, persistent and third party. Depending on their function, these cookies can further be categorized into functional or operational cookies, marketing or personalized cookies, third party cookies and their sub-categories. While functional cookies are necessary for a smooth user experience, other cookies, such as marketing cookies that track user preferences, are not essential for a website's performance. General Data Protection Regulation (GDPR) governs user data privacy and protection and stipulates that businesses require appropriate consent from website visitors before they are served any such cookies. 

We created this module to help you readily obtain consent from your website users for various cookies that will be served on your website. With the features available in this module, a website's visitor will be able to control the _types_ of data or cookies that can be stored and shared if you choose to offer that level of control.
        
## Why should I use it?
To keep up with the GDPR regulations and your company's privacy and cookie policies. GDPR regulations mandate that businesses need user consent before they can collect or track any visitor data. This tool helps you create a cookie consent banner out of the box. With the banner options, a user can choose to select the cookies (data) that can be collected by the website. The module lets you manage first party and third party cookies.    

## How does it work?

Altis Consent is an API that is designed to handle how cookies and other types of data stored locally on a user's computer are registered in the code. By creating a registry of cookies and local storage, and defining what those cookies are used for, the API allows the software to intelligently use only those cookies that a user has granted access to. 

In order for this to work, all cookies and local storage used on a site must be registered in the way mentioned in the Altis developer documentation.

## What does it do?

Out of the box, the Consent module handles all first-party cookies and local storage data. Using the controls provided on the Privacy page in the WP admin you can link your website's Privacy Policy and Cookie storage Policy page. There are options to control whether to grant the user a choice to select the types of cookies they want to consent to, or an option to allow all cookies or only functional cookies. You may also easily add a cookie consent banner message in the admin settings.  

In addition, a robust templating system and dozens of filters allow development teams to fully customize the options or the display of the banner, or only customize certain specific elements, based on a site's needs.

## What about third-party cookies?
The API allows management of third party cookies if it is configured so. Refer Developer Docs to see how this can be done. 

## Can I manage user data tracked through Altis' personalization features?
Altis' personalization features track user data that comprise first-party cookies and those will be treated as explained [above](https://github.com/humanmade/altis-consent/wiki#what-does-it-do). 

## Does the API help categorize the type of cookies?
Out of the box, [five categories](https://github.com/humanmade/altis-consent/wiki/WP-Consent-API#consent-categories) are provided functional, marketing, statistics, statistics-anonymous and preferences. More categories can be added via a filter as explained in the Dev Docs.