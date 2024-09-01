# FROM system-npm_runner-prod:hot as npm-runner

# FROM system-composer_runner-prod:hot as composer-runner

FROM nginx:1.19.6-alpine
WORKDIR /usr/share/nginx/html/
# COPY --from=npm-runner /plzt-backend/public/ .
# COPY --from=composer-runner /plzt-backend/public/ .

