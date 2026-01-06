FROM node:20-alpine

WORKDIR /projects/postbox

COPY package*.json package-lock.json* ./

RUN npm install --production

COPY . .

EXPOSE $VITE_SSR_PORT

CMD ["node", "server"]