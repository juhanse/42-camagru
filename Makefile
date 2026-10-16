# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Makefile                                           :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: juhanse <juhanse@student.42belgium.be>     +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2026/10/16 16:34:07 by juhanse           #+#    #+#              #
#    Updated: 2026/10/16 16:34:09 by juhanse          ###   ########.fr        #
#                                                                              #
# **************************************************************************** #

build:
	docker-compose up -d --build
	docker-compose exec web php /var/www/setup.php

start:
	docker-compose up -d

clean:
	docker-compose down -v --rmi all --remove-orphans

re: clean build

.PHONY: build start clean re
