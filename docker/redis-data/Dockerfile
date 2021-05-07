RUN wget -O redis.conf 'http://download.redis.io/redis-stable/redis.conf'
COPY redis.conf /usr/local/etc/redis/redis.conf
CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]