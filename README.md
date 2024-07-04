# Marketing Manager CRM

#### Installation Step
- clone this repository
- cp .env.example .env and update necessary credentials
- run fresh migraton and seeder
- run npm install 
- run 
  - npm run dev (while working locally)
  - npm run build (for production)
 

 #### Vonage Setup
 - run the command: nexmo app:create "Outbound Call code snippet" https://raw.githubusercontent.com/nexmo-community/ncco-examples/gh-pages/text-to-speech.json https://8433-195-26-125-117.ngrok-free.app/webhook/vonage --keyfile private.key
- Copy the ID should after application created and update VONAGE_CALL_APP_ID in .env file

#### Calander Setup
