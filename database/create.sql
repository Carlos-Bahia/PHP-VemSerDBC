-- Criação do banco de dados (opcional)
CREATE DATABASE IF NOT EXISTS CurriculoDB;
USE CurriculoDB;

-- Tabela Curriculum
CREATE TABLE Curriculum (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            age INT NOT NULL,
                            qualifications TEXT NOT NULL,
                            contact VARCHAR(11) NOT NULL,
                            github VARCHAR(255)
);

-- Tabela Academic_Background
CREATE TABLE Academic_Background (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     id_curriculum INT NOT NULL,
                                     scholarly ENUM('Ensino Médio', 'Ensino Técnico', 'Graduação', 'Pós-Graduação', 'Mestrado', 'Doutorado', 'Outros') NOT NULL,
                                     college VARCHAR(255) NOT NULL,
                                     course_name VARCHAR(255) NOT NULL,
                                     status ENUM('Em Andamento', 'Concluído', 'Interrompido', 'Não Informado') NOT NULL,
                                     FOREIGN KEY (id_curriculum) REFERENCES Curriculum(id)
);

-- Tabela Experience
CREATE TABLE Experience (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            id_curriculum INT NOT NULL,
                            company_name VARCHAR(255) NOT NULL,
                            position VARCHAR(255) NOT NULL,
                            admission_date DATE NOT NULL,
                            dismissal_date DATE,
                            description TEXT NOT NULL,
                            FOREIGN KEY (id_curriculum) REFERENCES Curriculum(id)
);

-- Tabela Knowledge
CREATE TABLE Curriculum_knowledge (
                                      id_curriculum INT,
                                      knowledge VARCHAR(255),
                                      PRIMARY KEY (id_curriculum, knowledge),
                                      FOREIGN KEY (id_curriculum) REFERENCES Curriculum(id)
);

-- Tabela Job_Offer
CREATE TABLE Job_Offer (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           name VARCHAR(255) NOT NULL,
                           description TEXT
);

drop table JOB_OFFER;

-- Tabela Knowledge
CREATE TABLE Job_knowledge (
                               id_job INT,
                               knowledge VARCHAR(255),
                               PRIMARY KEY (id_job, knowledge),
                               FOREIGN KEY (id_job) REFERENCES Job_Offer(id)
);

CREATE INDEX idx_curriculum_name ON Curriculum(name);
