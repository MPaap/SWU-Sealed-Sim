## Star Wars Unlimited Sealed Simulator

We are building an awesome Sealed sim for Star Wars Unlimited to play in real life
or in Karabast.

Feel free to help us build awesome new features or help us improve.

[Official site swusealed.com](https://swusealed.com/)

---

Build with Laravel and Vue.

To get fetch all the data after migration and seeding.

``
php artisan app:fetch-set all
``


### TODOS

- :heavy_check_mark: Remove Packdata, fun but not really needed
- Mobile mode
- :heavy_check_mark: User login
- - :heavy_check_mark: Login with Google
- - Login with Discord
- - Login with GitHub
- - :heavy_check_mark: Seed history
- - :heavy_check_mark: Exported Decklist History
- - Export link for Karabast instead of json


### Google Login steps

1. Visit the Google Cloud Console and create a new project.
2. Navigate to APIs & Services > Credentials.
3. Click Create Credentials > OAuth client ID and select Web application.
4. Add your Authorized redirect URIs: http://localhost/auth/google/callback.
5. Copy your Client ID and Client Secret.
