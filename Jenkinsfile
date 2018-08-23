pipeline {
  agent any
  stages {
    stage('Analyse') {
      agent {
        docker {
          image 'dsop/sonarqube-scanner'
          args '--entrypoint=""'
        }
      }
      steps {
        sh 'sonar-scanner -Dsonar.projectKey=monproj -Dsonar.sources=. -Dsonar.host.url=http://172.17.0.1:9000 -Dsonar.login=a90ddc2ed0298418c15c1f56789097d80aec88c8'
      }
    }
  }
}
