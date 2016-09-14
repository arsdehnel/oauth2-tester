# OAuth 2 Tester

Dockerized PHP script that I wrote to test OAuth 2 APIs at my company.  Pretty boring but it does the job.

## Requirements

* [Docker Engine](https://docs.docker.com/installation/)
* [Docker Compose](https://docs.docker.com/compose/)
* [Docker Machine](https://docs.docker.com/machine/) (Mac and Windows only)

## Running with _just_ Docker

```bash
# build the image from the local setup
$ docker build -t oauth2-tester .

# run that image with the right mappings and ports
$ docker run 
```

## Running with Docker Machine & Docker Compose

Set up a Docker Machine and then run:

```sh
$ docker-compose up
```

That's it! You can now access your configured sites via the IP address of the Docker Machine or locally if you're running a Linux flavour and using Docker natively.

## License

Copyright &copy; 2016 [Adam Dehnel](http://github.com/arsdehnel). Licensed under the terms of the [MIT license](LICENSE.md).
