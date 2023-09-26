# Golden Yellow Travel

## API Reference

#### Login (Post)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/login
```

| Arguments | Type   | Description                  |
| :-------- | :----- | :--------------------------- |
| email     | sting  | **Required** admin@gmail.com |
| password  | string | **Required** asdffdsa        |

## User Profile

#### Register (Post)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/register
```

| Arguments             | Type   | Description                  |
| :-------------------- | :----- | :--------------------------- |
| name                  | sting  | **Required** Post Malone     |
| email                 | sting  | **Required** admin@gmail.com |
| role                  | enum   | **Required** admin/staff     |
| password              | string | **Required** asdffdsa        |
| password_confirmation | string | **Required** asdffdsa        |

#### Own Profile (Get)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/your-profile
```

#### Check Specific User Profile (Get)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/user-profile/{id}
```

#### Check User Lists (Get)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/user-lists
```

#### Edit User Info(Get)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/edit
```

| Arguments | Type  | Description                       |
| :-------- | :---- | :-------------------------------- |
| name      | sting | **Required** Post Malone          |
| email     | sting | **Required** PostMalone@gmail.com |

#### Password Update (Put)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/update-password
```

| Arguments             | Type   | Description           |
| :-------------------- | :----- | :-------------------- |
| current_password      | sting  | **Required** asdffdsa |
| password              | string | **Required** asdffdsa |
| password_confirmation | string | **Required** asdffdsa |

#### Logout (Post)

```http
https://api.goldenyellowtravel.yolodigitalmyanmar.com/api/v1/logout
```
