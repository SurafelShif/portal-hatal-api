# Project Name: Portal API

## Docker Build and Save Instructions

### Building the Docker Image

To build the Docker image, use the following command format:

```bash
docker build . -t <project_name>-api-<todays_date>-<version> -f <dockerfile_name> --no-cache
```

-   **<project_name>**: The name of your project (e.g., portalhatal).
-   **<today's date>**: The date of the build in the format dd.mm.yyyy (e.g., 26.05.2024).
-   **<version>**: The version number of the build (e.g., 1).
-   **<dockerfile_name>**: The dockerfile you want to use (e.g., docker.balmas).

For Example:

```bash
docker build . -t portalhatal-api-26.05.2024-1 -f dockerfile.balmas --no-cache
```

### Saving the Docker Image

To save the Docker image as a tar file, use the following command format:

```bash
docker save -o <project_name>-api-<todays_date>-<version>.tar <project_name>-api-<todays_date>-<version>
```

-   **<project_name>**: The name of your project (e.g., portalhatal).
-   **<today's date>**: The date of the build in the format dd.mm.yyyy (e.g., 26.05.2024).
-   **<version>**: The version number of the build (e.g., 1).

For Example:

```bash
docker save -o portalhatal-api-26.05.2024-1.tar portalhatal-api-26.05.2024-1
```

### Additional Notes

Make sure to update the **<project_name>**, **<today's date>**, and **<version>** placeholders with your actual project details.

The --no-cache option is used to ensure that the build is done from scratch without using any cached layers.

The .tar file created by the docker save command contains all the information needed to load the image into another Docker environment using the docker load command.
