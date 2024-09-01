FROM nginx:1.19.6-alpine
COPY ./app-runner/docker-prod/fs/proxy/ /
