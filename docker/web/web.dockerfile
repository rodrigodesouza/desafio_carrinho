FROM nginx:1.25.4

COPY /docker/web/vhost.conf /etc/nginx/conf.d/default.conf