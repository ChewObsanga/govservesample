-- Machine Learning Database Schema for Social Services System

-- Table to store ML predictions
CREATE TABLE IF NOT EXISTS ml_predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_id INT NOT NULL,
    entity_type ENUM('user', 'application') NOT NULL,
    prediction_type ENUM('eligibility', 'processing_time', 'fraud_risk', 'resource_demand', 'beneficiary_clustering') NOT NULL,
    prediction_data JSON NOT NULL,
    confidence_score DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_entity (entity_id, entity_type),
    INDEX idx_prediction_type (prediction_type),
    INDEX idx_created_at (created_at)
);

-- Table to store ML model performance metrics
CREATE TABLE IF NOT EXISTS ml_model_performance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_name VARCHAR(100) NOT NULL,
    model_version VARCHAR(20) NOT NULL,
    accuracy_score DECIMAL(5,4) DEFAULT 0.0000,
    precision_score DECIMAL(5,4) DEFAULT 0.0000,
    recall_score DECIMAL(5,4) DEFAULT 0.0000,
    f1_score DECIMAL(5,4) DEFAULT 0.0000,
    training_samples INT DEFAULT 0,
    testing_samples INT DEFAULT 0,
    training_time_seconds DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_model (model_name, model_version),
    INDEX idx_created_at (created_at)
);

-- Table to store ML training data
CREATE TABLE IF NOT EXISTS ml_training_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_type ENUM('eligibility', 'processing_time', 'fraud_detection') NOT NULL,
    features JSON NOT NULL,
    target_value VARCHAR(100) NOT NULL,
    data_quality_score DECIMAL(5,2) DEFAULT 0.00,
    source_table VARCHAR(50) NOT NULL,
    source_record_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_data_type (data_type),
    INDEX idx_source (source_table, source_record_id),
    INDEX idx_created_at (created_at)
);

-- Table to store ML feature importance
CREATE TABLE IF NOT EXISTS ml_feature_importance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_name VARCHAR(100) NOT NULL,
    feature_name VARCHAR(100) NOT NULL,
    importance_score DECIMAL(10,6) DEFAULT 0.000000,
    feature_type ENUM('numerical', 'categorical', 'binary') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_model (model_name),
    INDEX idx_feature (feature_name),
    INDEX idx_importance (importance_score DESC)
);

-- Table to store ML predictions history for audit
CREATE TABLE IF NOT EXISTS ml_predictions_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prediction_id INT NOT NULL,
    old_prediction_data JSON,
    new_prediction_data JSON,
    change_reason VARCHAR(255),
    changed_by INT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prediction_id) REFERENCES ml_predictions(id) ON DELETE CASCADE,
    INDEX idx_prediction_id (prediction_id),
    INDEX idx_changed_at (changed_at)
);

-- Table to store ML model configurations
CREATE TABLE IF NOT EXISTS ml_model_configs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_name VARCHAR(100) NOT NULL,
    model_type ENUM('classification', 'regression', 'clustering') NOT NULL,
    hyperparameters JSON NOT NULL,
    feature_config JSON NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_model_name (model_name),
    INDEX idx_is_active (is_active)
);

-- Table to store ML data quality metrics
CREATE TABLE IF NOT EXISTS ml_data_quality (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(100) NOT NULL,
    column_name VARCHAR(100) NOT NULL,
    data_type VARCHAR(50) NOT NULL,
    null_count INT DEFAULT 0,
    unique_count INT DEFAULT 0,
    min_value VARCHAR(255),
    max_value VARCHAR(255),
    avg_value DECIMAL(15,6),
    quality_score DECIMAL(5,2) DEFAULT 0.00,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_table_column (table_name, column_name),
    INDEX idx_quality_score (quality_score DESC)
);

-- Insert default ML model configurations
INSERT INTO ml_model_configs (model_name, model_type, hyperparameters, feature_config) VALUES
('eligibility_predictor', 'regression', 
 '{"algorithm": "least_squares", "normalize": true, "max_iterations": 1000}', 
 '{"features": ["age", "monthly_income", "family_size", "employment_status", "disability_status", "education_level", "housing_condition", "medical_conditions"]}'),
('fraud_detector', 'classification', 
 '{"algorithm": "svc", "kernel": "rbf", "C": 1.0, "gamma": "scale"}', 
 '{"features": ["income_discrepancy", "document_authenticity", "application_frequency", "address_verification", "employment_verification"]}'),
('processing_time_predictor', 'regression', 
 '{"algorithm": "least_squares", "normalize": true}', 
 '{"features": ["service_type", "document_completeness", "applicant_age", "family_size", "urgency_level"]}'),
('beneficiary_clusterer', 'clustering', 
 '{"algorithm": "kmeans", "k": 4, "max_iterations": 300}', 
 '{"features": ["age", "monthly_income", "family_size", "disability_score", "education_level"]}');

-- Create views for easy ML data access
CREATE OR REPLACE VIEW ml_dashboard_summary AS
SELECT 
    prediction_type,
    COUNT(*) as total_predictions,
    AVG(JSON_EXTRACT(prediction_data, '$.confidence')) as avg_confidence,
    DATE(created_at) as prediction_date
FROM ml_predictions 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY prediction_type, DATE(created_at)
ORDER BY prediction_date DESC, prediction_type;

CREATE OR REPLACE VIEW ml_eligibility_trends AS
SELECT 
    DATE(created_at) as date,
    AVG(JSON_EXTRACT(prediction_data, '$.eligibility_score')) as avg_score,
    COUNT(*) as total_applications,
    AVG(JSON_EXTRACT(prediction_data, '$.confidence')) as avg_confidence
FROM ml_predictions 
WHERE prediction_type = 'eligibility'
AND created_at >= DATE_SUB(NOW(), INTERVAL 90 DAY)
GROUP BY DATE(created_at)
ORDER BY date;

CREATE OR REPLACE VIEW ml_fraud_risk_summary AS
SELECT 
    JSON_EXTRACT(prediction_data, '$.risk_level') as risk_level,
    COUNT(*) as count,
    AVG(JSON_EXTRACT(prediction_data, '$.risk_score')) as avg_risk_score,
    AVG(JSON_EXTRACT(prediction_data, '$.confidence')) as avg_confidence
FROM ml_predictions 
WHERE prediction_type = 'fraud_risk'
GROUP BY risk_level
ORDER BY avg_risk_score DESC;
