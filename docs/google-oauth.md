# Google OAuth

Go to https://console.developers.google.com/apis/

- Click **Create a Project**
  - Fill in a name
  - Click **Create**
- Select the newly created project (if not already done so)
- Click **Enable API**
- Click **Credentials** on the left pane
- Click **OAuth consent screen**
  - Fill in email, name, and anything else
  - Click **Save**
- Click **Create Credentials**, then **OAuth client ID**
  - Choose **Web Application**
  - Fill in name
  - For **Authorized JavaScript origins**, put in the base website URL
    - e.g., https://myhost
  - For **Authorized Redirect URIs**, put in the base website URL + `mahapanel/login`
    - e.g., https://myhost/mahapanel/login
  - Click Save

**Client ID** and **Client secret** can be copied to `AUTH_GOOGLE_CLIENT_ID` and `AUTH_GOOGLE_CLIENT_SECRET` in `.env`.
