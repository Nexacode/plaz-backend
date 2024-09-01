FROM node:18.0.0-alpine3.14
WORKDIR /app/
RUN npm install -g npm@9.6.6
RUN apk add g++ make python2
COPY ./app/package.json .
COPY ./app/package-lock.json .
RUN npm ci
COPY ./app/ .
