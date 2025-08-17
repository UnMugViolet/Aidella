pipeline {
    agent any
    
    environment {
        NODE_ENV = 'production'
        DOCKER_REGISTRY = 'unmugviolet'
        DOCKER_BUILDKIT = '0'
    }
    tools {
        nodejs 'Main NodeJS'
    }
    stages {
        stage('SCM') {
            steps {
                git url: 'git@github.com:UnMugViolet/Aidella.git', branch: 'main'
            }
        }

        stage('SonarQube analysis') {
            steps {
                withSonarQubeEnv('Sonar-Server') {
                    sh "${tool 'SonarScanner'}/bin/sonar-scanner"
                }
            }
        }

        // Wait for the SonarQube analysis to be completed by getting the webhook response
        stage('Quality Gate') {
            steps {
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Check Docker') {
            steps {
                sh 'docker --version'
                sh 'docker info'
            }
        }

        stage('Build Image') { 
            steps {
                script {
                    try {
                        // Check if Dockerfile exists
                        sh 'ls -la ./Dockerfile || echo "Dockerfile not found"'

                        def imageName = "${DOCKER_REGISTRY}/aidella:latest"
                        sh """
                            docker build -t ${imageName} \
                            --build-arg NODE_ENV='${env.NODE_ENV}' \
                            .
                        """

                        env.IMAGE_NAME = imageName

                        echo "Docker image built successfully: ${env.IMAGE_NAME}"

                    } catch (Exception e) {
                        error "Failed to build Docker image: ${e.getMessage()}"
                    }
                }
            }
        }
        
        stage('Push Image') {
            steps {
                script {
                    // Login to Docker Hub and push image
                    docker.withRegistry('https://index.docker.io/v1/', 'dockerhub-credentials') {
                        def imageName = docker.image("${env.IMAGE_NAME}")
                        imageName.push()
                        echo "Successfully pushed ${imageName} to Docker Hub"
                    }
                }
            }
        }
        
        stage('Deploy') {
            steps {
                sshPublisher(
                    publishers: [
                        sshPublisherDesc(
                            configName: 'Infomaniak',
                            transfers: [
                                sshTransfer(
                                    execCommand: '''
                                        cd ~/websites/aidella &&
                                        docker compose down &&
                                        docker compose pull &&
                                        docker compose up -d --build &&
                                        sleep 10 &&
                                        docker exec aidella-app make deploy &&
                                        docker system prune -f &&
                                        docker image prune -f &&
                                        docker volume prune -f &&
                                        docker network prune -f
                                    '''
                                )
                            ]
                        )
                    ]
                )
            }
        }
    }
    
    post {
        always {
            emailext (
                mimeType: 'text/html',
                subject: "[${env.JOB_NAME}] Build #${env.BUILD_NUMBER} - ${currentBuild.currentResult}",
                body: """<div style="background-color: black; padding: 5px 20px; display: inline-block;">
                            <table style="color: white; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 0;">
                                        <img src="https://www.jenkins.io/images/logos/jenkins/jenkins.png" alt="Jenkins logo" style="width: 29px; height: 40px;"/>
                                    </td>
                                    <td style="padding-left: 0.5rem;">
                                        <h2 style="margin: 0;">${env.JOB_NAME} - Build # ${env.BUILD_NUMBER} - ${currentBuild.currentResult}</h2>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <p>The build was ${currentBuild.currentResult}. Check the <a href="${env.BUILD_URL}console">Console output</a> for details.</p>
                        <p>Check <a href="${env.BUILD_URL}">Jenkins build</a> to view the results.</p>""",
                to: "${env.VITE_APP_ADMIN_EMAIL_ADDRESS}"
            )
        }
    }
}
