FROM php:7.3-fpm-buster

ARG TIMEZONE=Europe/Paris
ARG PROJECT_FOLDER=/var/www/
ARG JWT_FOLDER=/etc/jwt

RUN ln -fs /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && dpkg-reconfigure --frontend noninteractive tzdata

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libicu-dev \
    software-properties-common \
    apt-utils \
    git \
    zip unzip \
    openssl \
    acl \
    --no-install-recommends && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        intl \
        mbstring \
        pdo \
        pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./.env* ${PROJECT_FOLDER}

RUN mkdir -p ${JWT_FOLDER}

RUN jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' ${PROJECT_FOLDER}.env | cut -f 2 -d ''='')} && \
    echo "$jwt_passphrase" | openssl genpkey -out ${JWT_FOLDER}/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 && \
    echo "$jwt_passphrase" | openssl pkey -in ${JWT_FOLDER}/private.pem -passin stdin -out ${JWT_FOLDER}/public.pem -pubout

RUN jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' ${PROJECT_FOLDER}.env.test | cut -f 2 -d ''='')} && \
    echo "$jwt_passphrase" | openssl genpkey -out ${JWT_FOLDER}/private-test.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 && \
    echo "$jwt_passphrase" | openssl pkey -in ${JWT_FOLDER}/private-test.pem -passin stdin -out ${JWT_FOLDER}/public-test.pem -pubout

RUN setfacl -R -m u:www-data:rX -m u:root:rwX ${JWT_FOLDER} && \
    setfacl -dR -m u:www-data:rX -m u:root:rwX ${JWT_FOLDER}

WORKDIR ${PROJECT_FOLDER}

CMD ["php-fpm"]
