### Integration

#### PHPStorm integration

* `File > Settings > Tools > External Tools`
* Click green add icon
* Name it like you wish
* In `Program` input select your project bin/console path
* Under `Arguments` input paste unigen:generate $FilePath$
* Select your working directory
* To run generator for given class just click `Tools > External Tools`

##### Integrate with docker

* Change `Program` input to docker
* Update `Arguments` input  to your needs e.g. `exec -u DOCKER_USER CONTAINER_NAME $ProjectName$/bin/console unigen:generate $ProjectName$/$FileRelativePath$`

##### Shortcut

In `Appearance & Behavior > Keymap` you can assign any keyboard binding to run tool for you


