Movie Review App - Final Project (COSC4806)
===========================================

Overview
--------
This is my final project for COSC4806. The app lets users:
- Search for any movie and see its details (using OMDb API)
- Log in and rate movies from 1 to 5
- Get an AI-generated movie review using Google Gemini AI

Anyone can search movies, but only logged-in users can rate or get a review.

Tools Used
----------
- **Database:** MariaDB on filess.io
- **Coding Platform:** Replit

**Get API Keys**
   - We need to Register at [OMDb](https://www.omdbapi.com/apikey.aspx) to get OMDb API key.
   - Also Register at [Google AI Studio](https://aistudio.google.com/app/apikey) for Gemini AI API key.

App functionality 
-----------------
- **Search:** Anyone can search for a movie. The app shows info like title, year, IMDb rating, Metascore, etc.
- **Rate:** Logged-in users can rate movies from 1 to 5 (whole numbers only). The app checks the rating before saving.
- **AI Review:** After rating, users get a review created by Google Gemini AI, using their rating and the movie name.
- **Design:** The site is responsive and uses Bootstrap for easy navigation.

Project Folders
---------------
- `app/controllers/`
- `app/models/`
- `app/views/` 
- `app/core/`

## Database Tables

- Please run the SQL script provided to create all required tables.
- Main tables:
  - `mv_users`: Stores user account info (username, password hash, role, created time)
  - `mv_ratings`: Stores each user’s rating for a movie
  - `mv_login_logs`: Logs every user login event (only used by admin)
- Foreign keys and timestamps are included for better data tracking and database relationships.
