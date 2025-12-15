## # 처음 설치시 세팅

```
## php 8.1 이상

# composer 설치
$ composer install

# .env 파일 만들기
$ cp .env.example .env
$ php artisan key:generate

# 폴더 권한 리눅스 서버에서만 윈도우에서는 불필요
$ chmod -R o+rw bootstrap/cache
$ chmod -R o+rw storage

# /storage/app/data 폴더 생성후 아래 실행
# 파일 시스템
$ php artisan storage:link

$ php artisan migrate --seed

# 디버그바 (필요하면 설치)
$ composer require barryvdh/laravel-debugbar --dev

# 테이블러 아이콘
$ composer require ryangjchandler/blade-tabler-icons

# 개발확인 모바일에서 와이파이의 IP주소로 접근
$ php artisan serve --host=0.0.0.0 --port=8000
```
