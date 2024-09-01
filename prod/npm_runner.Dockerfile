FROM node:18.0.0-alpine3.14
WORKDIR /plzt-backend/
RUN npm install -g npm@9.6.6
RUN apk add g++ make python2
COPY ./plzt-backend/package.json .
COPY ./plzt-backend/package-lock.json .
RUN npm ci
COPY ./plzt-backend/ .
