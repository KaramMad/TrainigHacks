# 🏋️‍♂️ TrainigHacks
_made with_ ⬇ <br/>  [<img src="https://laravel.com/img/logomark.min.svg" alt="Laravel" width="75">](https://laravel.com/) 
<br/>
<br/>
✨A BackEnd Laravel-based application that helps you stay fit with personalized home workouts. 
Track your progress, set goals, and stay motivated from the comfort of your home!✨

![Laravel](https://img.shields.io/badge/Laravel-10-orange)
![PHP](https://img.shields.io/badge/PHP-%5E8.2-blue)

## 📋 Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)


## 🌟 Features in Depth

   - Seamless Payments with MyFatoorah: Integrated MyFatoorah API to handle secure and reliable payments for subscriptions, products, and services within the platform.

   - Real-Time Notifications: Leveraging Firebase for instant push notifications, ensuring users stay updated with their fitness goals, meal plans, order statuses, and community         interactions.

   - Personalized Exercise Plans: Diverse exercise routines tailored to user fitness levels, targeted muscles, and specific categories. The platform suggests exercises based on         user health conditions and activity levels, providing a unique and effective workout experience.

   - Premium Subscription with Coach Access: Unlock advanced features with a premium subscription, including direct access to personal coaching, exclusive workout plans, and            priority support.

   - Smart Meal Plans with Health Alerts: Comprehensive meal planning that warns users about meals that could exacerbate specific health conditions, such as high blood pressure,        by flagging meals high in salt or other risky ingredients.

   - Dynamic Media and Social Interaction: Engage with a vibrant community through a media section and chat functionality powered by Firebase. Posts and comments are monitored,         with users banned for inappropriate content, ensuring a positive and supportive environment.

   - Comprehensive Admin and Coach Panel: Admins and coaches have dedicated control panels, allowing them to manage their respective domains effectively, from user management to         content creation and moderation.

   - Detailed Reports and Analytics: Users, coaches, and admins receive daily and weekly reports to track progress, engagement, and overall performance, ensuring data-driven             insights for continuous improvement.

   - Integrated Sports Shop: Browse and shop for sports gear and fitness equipment within the platform. Features include advanced product filtering, easy order placement, order         tracking, and the ability to cancel orders. Stay informed with real-time updates on order status and delivery.

## 🎯 Usage
1. Authenticating with Google Accounts

    Easily sign up or log in using your Google account, thanks to seamless OAuth2 integration with Laravel Passport. Enjoy secure and quick access to the platform without needing to create a separate account.

2. Making Payments

    Use the integrated MyFatoorah API to make secure payments for subscriptions, sports shop purchases, or other services directly through the platform.

3. Staying Informed with Notifications

    Receive real-time notifications via Firebase for workout reminders, meal plan updates, order statuses, and community activity, ensuring you never miss an important update.

4. Customizing Your Workout

    Access a variety of exercise routines tailored to your fitness level, targeted muscle groups, and specific categories.
    Get personalized exercise recommendations based on your health conditions and activity levels, helping you achieve optimal results.

5. Upgrading to Premium

    Subscribe to the premium section for exclusive benefits, including personalized coaching, advanced workout plans, and priority support from fitness experts.

6. Planning Your Meals

    Explore meal options that fit your dietary needs. The platform automatically warns you of meals that could negatively impact your health conditions, such as high-salt meals for users with high blood pressure.

7. Engaging with the Community

    Participate in the media section by sharing posts and engaging in chats. The platform ensures a positive environment by banning users who post inappropriate content.

8. Managing with the Admin/Coach Panel

    Admins and coaches can manage users, content, and interactions through dedicated control panels, ensuring smooth operation and content moderation within their areas.

9. Tracking Progress

    View detailed daily and weekly reports on your fitness progress, meal adherence, and engagement, helping you stay on track and make informed decisions.

10. Shopping for Sports Gear

    Browse the integrated sports shop to find the right gear for your workouts.
    Filter products by categories, brands, or price ranges, place orders easily, and track your order status in real-time.
    Cancel orders if needed and receive updates on delivery and order fulfillment.
## 🤝 Contributing

Contributions are welcome! Please fork this repository, make your changes, and submit a pull request. Ensure your code follows the project’s coding guidelines.

## 📜 License

-This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

to startup (windows):

composer install

cp .env.example .env

php artisan key:generate

setup database name in .env file and xampp then run: php artisan migrate

