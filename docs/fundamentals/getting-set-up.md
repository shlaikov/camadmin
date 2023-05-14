# Getting set up



<details>

<summary>Step1: Install app</summary>

To deploy a Docker container to your server, you can follow these general steps:

1. Install Docker on your server if it's not already installed.
2. Pull the Docker image for your application from a Docker registry, or build it locally if you have the Dockerfile.
3. Create a Docker container by running the image using the `docker run` command. You can use the `--name` flag to give your container a name, and the `-p` flag to expose ports to the host machine.
4. Customize your Docker container by providing environment variables or configuration files to your application.
5. Start your Docker container by running the `docker start` command.

Here is an example of how to deploy a Docker container to your server:

1. Install Docker on your server by following the instructions for your operating system.
2. Pull the Docker image for your application from a Docker registry. For example, to pull the official Camunda image, you can run the following command:

```
docker pull shlaikov/camadmin:latest
```

3. Create a Docker container by running the image using the `docker run` command. For example, to create a container named "camadmin" and expose the Camadmin web interface on port 8080, you can run the following command:

```
docker run -d --name camadmin -p 8080:8080 shlaikov/camadmin:latest
```

4. Customize your Docker container by providing environment variables or configuration files to your application. For example, you can set the `MINIO_BUCKET` environment variable to specify cloud storage.

```
docker run -d --name camadmin -p 8080:8080 -e FILESYSTEM_CLOUD=minio MINIO_BUCKET="business-processes" MINIO_URL=http://minio:9000 shlaikov/camadmin:latest
```

5. Start your Docker container by running the `docker start` command.

```
docker start camadmin
```

Your Docker container should now be running on your server, and you can access the Camadmin web interface by navigating to http://\<your\_server\_ip>:8080/login in a web browser.

</details>

<details>

<summary>Step 2: Inviting your team</summary>



</details>

<details>

<summary>Step 3: Adding your first instance</summary>



</details>
