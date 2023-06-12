![Docker](/docker/lamp/assets/images/main.png "Docker LAMP STACK")

## Prerequisites

### Linux and Windows

Verify the following are installed/added in your system:

- <a href="https://docs.docker.com/get-docker/">docker latest version</a> (v20.x.x or higher) installed and should be running with non-root(arbitary) user

- <a href="https://docs.docker.com/compose/install/">docker-compose latest version</a> (1.29.2) installed and should be running with non-root(arbitary) user

- <a href="https://git-scm.com/downloads">GIT latest version</a> (v2.33.x or higher)

- Your ssh key is added to your gitlab account

- <a href="https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker">docker extension</a> (publisher:microsoft) installed in Visual Studio Code


### Windows

- GIT must be installed with Git Bash

- WSL2 (Windows Subsystem for Linux) should be enabled in docker

- Port 80 should be available (Check if IIS is running). If not, choose other port to run the webserver.

- set Docker to be started automatically on system startup


> For windows, always use "git bash" to run commands. If you face any issue while running commands then, need to run the bash command adding "winpty".

<br>

## Setup Lamp

Follow the below given steps to setup Lamp:

1. Go to your favourite path where you want to setup Lamp (i.e /home/user/my_env/) and execute the following command:
   ```
   git archive --format=tar --remote=git@rxgit.radixweb.in:rxprojects/opscore/php/misc.git master -- docker/lamp/* | tar xf -
   ```

1. Run the below command:
   
   ```
   cd docker/lamp
   ```

1. Configure environment using the below command:
   
   ```
   cp sample.env .env
   ```

   After running the above command, you can have a look at .env configuration. We don't recommend any changes in this however, one can only change ports if other services are running on common used ports (i.e Port 80)
   <br>

1. Run the final command to start up your services:
   
   ```
   HOSTNAME=${HOSTNAME} docker-compose up -d
   ``` 
<br>

After the setup is successfully installed, you will have the following things up and running:

- **Webserver:** accessible at http://localhost

- **PhpMyAdmin:** accessible at http://localhost:8081

<br>

Provide the permissions to windows platform while setup:
```
winpty docker exec -it OPS-database sh -c 'chmod 644 /etc/mysql/conf.d/my.cnf'
```

**Note:** Need to run the bash command adding "winpty" if you face any issue while giving permissions.

<br>

## Database configuration

By default, only one database will be created named '**ops**' but, you can create as many databases as you need. 

Login credentials for the database:

**username:** root / radixdev

**password:** deep70

**password:** radixdev

> One should grant privilages for newly created database to arbitary user 'radixdev' by running below query
```
GRANT ALL PRIVILEGES ON `dbname` . * TO 'radixdev'@'%';
```

<br>

For further configurations, click <a href="https://rxgit.radixweb.in/rxprojects/opscore/php/opscore/-/wikis/Docker-Configuration" target="_blank">here</a>. 
