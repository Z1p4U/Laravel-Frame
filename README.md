# Template

## API Reference

#### Login (Post)

```http
http://127.0.0.1:8000/api/v1/login
```

| Arguments | Type   | Description                  |
| :-------- | :----- | :--------------------------- |
| email     | sting  | **Required** admin@gmail.com |
| password  | string | **Required** asdffdsa        |

## User Profile

#### Register (Post)

```http
http://127.0.0.1:8000/api/v1/register
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
http://127.0.0.1:8000/api/v1/your-profile
```

#### Check Specific User Profile (Get)

```http
http://127.0.0.1:8000/api/v1/user-profile/{id}
```

#### Check User Lists (Get)

```http
http://127.0.0.1:8000/api/v1/user-lists
```

#### Edit User Info(Get)

```http
http://127.0.0.1:8000/api/v1/edit
```

| Arguments | Type  | Description                       |
| :-------- | :---- | :-------------------------------- |
| name      | sting | **Required** Post Malone          |
| email     | sting | **Required** PostMalone@gmail.com |

#### Password Update (Put)

```http
http://127.0.0.1:8000/api/v1/update-password
```

| Arguments             | Type   | Description           |
| :-------------------- | :----- | :-------------------- |
| current_password      | sting  | **Required** asdffdsa |
| password              | string | **Required** asdffdsa |
| password_confirmation | string | **Required** asdffdsa |

#### Logout (Post)

```http
http://127.0.0.1:8000/api/v1/logout
```

## Media

### Photo

#### Store Photo (Post)

```https
http://127.0.0.1:8000/api/v1/photo/store
```

| Arguments | Type  | Description     |
| :-------- | :---- | :-------------- |
| photos[]  | array | **Required** [] |

#### Get Photo (Get)

```https
http://127.0.0.1:8000/api/v1/photo/list
```

#### Show Photo (Get)

```https
http://127.0.0.1:8000/api/v1/photo/show/{id}
```

#### Delete Photo (Del)

```https
http://127.0.0.1:8000/api/v1/photo/delete/{id}
```

#### Multiple Photo Delete (Post)

```https
http://127.0.0.1:8000/api/v1/photo/multiple-delete
```

| Arguments | Type  | Description          |
| :-------- | :---- | :------------------- |
| photos    | array | **Required** [1,2,3] |

###### Note : ID must be in Array.

#### Trash (Get)

```https
http://127.0.0.1:8000/api/v1/photo/trash
```

#### Restore (Get)

```https
http://127.0.0.1:8000/api/v1/photo/restore/{id}
```

#### Force Delete (Get)

```https
http://127.0.0.1:8000/api/v1/photo/force-delete/{id}
```

#### Clear Trash (Get)

```https
http://127.0.0.1:8000/api/v1/photo/clear-trash
```
