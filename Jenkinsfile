pipeline {
    agent any
    
    environment {
        NODE_ENV = 'production'
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
        
        stage('Build Assets') {
            steps {
                sh 'npm install --include=dev'
                sh 'npm run build'
            }
        }
        
        stage('SonarQube analysis') {
            steps {
                withSonarQubeEnv('Sonar-Server') {
                    sh "${tool 'SonarScanner'}/bin/sonar-scanner"
                }
            }
        }
        
        stage('Quality Gate') {
            steps {
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }
        stage('Publish') {
            steps {
                sshPublisher(
                    continueOnError: false, 
                    failOnError: true, 
                    publishers: [
                        sshPublisherDesc(
                            configName: 'Hostinger',
                            transfers: [
                                sshTransfer(
                                    sourceFiles: '**/*',
                                    excludes: '''
                                        **/.env.example,
                                        **/node_modules/**,
                                        **/Examples/**,
                                        **/coverage/**,
                                        **/tests/**,
                                        **/.git/**,
                                        **/storage/logs/**,
                                        **/bootstrap/cache/**,
                                        **/.phpunit.result.cache,
                                        **/composer.lock,
                                        **/package-lock.json,
                                        **/yarn.lock,
                                        **/.gitignore,
                                        **/README.md,
                                        **/Jenkinsfile
                                        **/sonar-project.properties
                                        **/phpunit.xml
                                        **/storage/app/public/**
                                    ''',
                                    remoteDirectory: '/domains/elevage-canin-vosges.fr/',
                                    removePrefix: '', 
                                    cleanRemote: false,
                                    makeEmptyDirs: true,
                                    flatten: false, 
                                    noDefaultExcludes: false
                                )
                            ],
                            usePromotionTimestamp: false,
                            verbose: true 
                        )
                    ]
                )
            }
        }
        
        stage('Post Deploy') {
            steps {
                sshPublisher(
                    publishers: [
                        sshPublisherDesc(
                            configName: 'Hostinger',
                            transfers: [
                                sshTransfer(
                                    execCommand: '''
                                        cd ~/domains/elevage-canin-vosges.fr/ &&
                                        make deploy
                                    '''
                                )
                            ]
                        )
                    ]
                )
            }
        }
    }
}
