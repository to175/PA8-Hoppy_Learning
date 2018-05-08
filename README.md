![](http://site3066.cfn.acsitefactory.com/sites/g/files/awx4001/f/images/LightBulb_1469041529.png)

**Table of Contents**

1. Introduction
2. Features
3. Screenshots
4. Technologies
   1. Security
   1. Architecture

# Hoppy Learning
Hoppy Learning is the match of 8 students of EFREI Paris Engineering School. The project was first aiming at creating an e-learning VR-enriched website for people with disabilities. Everybody could have learned languages on "Hope/Happy Learning".
However, the team has taken a turnover and decided to not work only on languages but on technical vocabulary. The learning will be in English first, the students will learn specific vocabulary regarding to their speciality.



# Features
- Signup and login with email verification;
- Profile dashboard
 - Table with progress : rank, scores
 - Bookmarks on courses
 - Delete and update account
 - Premium plans subscription
- Modules with lectures inside
- Lectures with courses (videos) inside
- A quiz for each lecture
- Forum with private messages (has been removed)

# Screenshots
> User Dashboard screenshot
![](http://theofleury.fr/hoppy/images/git/userdashboard.png)

> Lectures screenshot
![](http://theofleury.fr/hoppy/images/git/lectures.png)


> Courses screenshot
![](http://theofleury.fr/hoppy/images/git/courses.png)


> Quiz screenshot
![](http://theofleury.fr/hoppy/images/git/quiz.png)


# Technologies

## Security
We used SHA256 hash to encrypt the passwords. The password will be encrypted at each connection and compared with the stored one.
The database is accessed with PDO and every request are prepared for more security.
The user can access its data at any time. He can modify them of delete his account.

## Architecture
The website is HTML5-ready for SEO optimisation. The structure has been created to match with the robots search engines. The style of the website is made with Bootstrap CSS3 library.
