# Getting set up



<details>

<summary>Step 1: Install app</summary>

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

<summary>Step 2: Create superuser</summary>

To create a super user via Docker shell in a Camadmin, you can follow these steps:

1. Open the terminal or command prompt and navigate to the root directory of your Laravel project.
2. Run the command `docker-compose exec camadmin php artisan cmdmn:create_superuser` to open a shell in the Docker container for your Laravel application.

Once you have completed these steps, you should have a new super user with the email 'admin@example.com' and the password 'password', and assigned the 'super admin' role. You can use these credentials to log in to your application and access the super admin features.

</details>

<details>

<summary>Step 3: Inviting your team</summary>

To invite a team member in Camadmin, you can follow these steps:

1. Log in application as an admin or a user with team management permissions.
2. Navigate to the "Teams" section of your application and select the team that you want to invite a member to.
3. Click on the "Members" tab and click the "Add Member" button.
4. Enter the email address of the person you want to invite and select the role you want to assign to them. You can choose from "Administrator", "Developer", or "Member".
5. Click the "Send Invitation" button to send the invitation email to the team member.
6. The team member will receive an email with a link to accept the invitation and join the team. They will need to click on the link and create a new account or log in with an existing account if they have one.
7. Once the team member has accepted the invitation and joined the team, they will be able to access the team's resources based on the role you assigned to them.

That's it! You have successfully invited a team member to Camadmin.

</details>

<details>

<summary>Step 4: Adding your first instance</summary>



</details>
