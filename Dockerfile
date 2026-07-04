FROM php:8.2-apache

# 1. Instala extensões necessárias para o banco de dados (PDO e MySQLi)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 2. Ativa o mod_rewrite do Apache (essencial se você usar arquivos .htaccess para proteger pastas ou URLs amigáveis)
RUN a2enmod rewrite

# 3. Copia todo o código do seu sistema (onde este Dockerfile está) para a pasta web do Apache
COPY . /var/www/html/

# 4. Ajusta as permissões para que o servidor consiga ler e gravar arquivos (importante se seu CRM tiver upload de documentos de clientes)
RUN chown -R www-data:www-data /var/www/html