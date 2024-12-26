from flask import Flask, render_template, request, jsonify, send_from_directory
import pandas as pd
import numpy as np
import os
import json
import joblib
import base64
import io
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
import datetime
from sklearn.pipeline import Pipeline
import sys
sys.executable
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense
from tensorflow.keras.wrappers.scikit_learn import KerasClassifier, KerasRegressor
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import (
    LabelEncoder, 
    StandardScaler, 
    OneHotEncoder
)
from sklearn.compose import ColumnTransformer
from sklearn.impute import SimpleImputer
from sklearn.ensemble import RandomForestClassifier, RandomForestRegressor
from sklearn.linear_model import LogisticRegression, LinearRegression
from sklearn.metrics import (
    classification_report, 
    accuracy_score, 
    confusion_matrix, 
    mean_squared_error, 
    r2_score
)


app = Flask(__name__)
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB max file size

# Global variables for storing dataset and model
dataset_path = None
model_results = {}

def analyze_dataframe(df):
    """
    Comprehensively analyze DataFrame columns
    """
    column_analysis = {}
    for column in df.columns:
        unique_count = df[column].nunique()
        dtype = str(df[column].dtype)
        
        # Determine column type
        if pd.api.types.is_numeric_dtype(df[column]):
            column_type = 'numeric'
        elif pd.api.types.is_categorical_dtype(df[column]) or unique_count < 10:
            column_type = 'categorical'
        else:
            column_type = 'text'
        
        column_analysis[column] = {
            'type': column_type,
            'unique_values': unique_count,
            'dtype': dtype,
            'sample_values': df[column].sample(min(5, len(df))).tolist()
        }
    
    return column_analysis

def create_preprocessing_pipeline(df, feature_columns, target_column):
    """
    Create a preprocessing pipeline based on column types
    """
    numeric_features = []
    categorical_features = []
    
    for col in feature_columns:
        if pd.api.types.is_numeric_dtype(df[col]):
            numeric_features.append(col)
        else:
            categorical_features.append(col)
    
    # Preprocessing for numeric features
    numeric_transformer = Pipeline(steps=[
        ('imputer', SimpleImputer(strategy='median')),
        ('scaler', StandardScaler())
    ])
    
    # Preprocessing for categorical features
    categorical_transformer = Pipeline(steps=[
        ('imputer', SimpleImputer(strategy='most_frequent')),
        ('onehot', OneHotEncoder(handle_unknown='ignore'))
    ])
    
    # Combine preprocessing steps
    preprocessor = ColumnTransformer(
        transformers=[
            ('num', numeric_transformer, numeric_features),
            ('cat', categorical_transformer, categorical_features)
        ])
    
    return preprocessor

@app.route('/')
def index():
    return render_template('gestionmachin.php')

@app.route('/technicien')
def expert_dashboard():
    return render_template('technicien.php')

@app.route('/upload', methods=['POST'])
def upload():
    global dataset_path
    file = request.files['file']
    
    if not file:
        return jsonify(message="No file uploaded")
    
    try:
        # Ensure uploads directory exists
        os.makedirs('uploads', exist_ok=True)
        dataset_path = os.path.join('uploads', file.filename)
        file.save(dataset_path)
        
        # Read CSV
        df = pd.read_csv(dataset_path)
        
        # Analyze columns
        column_analysis = analyze_dataframe(df)
        
        return jsonify({
            'message': "Dataset loaded successfully", 
            'columns': list(df.columns),
            'column_analysis': column_analysis,
            'sample_data': df.head(5).to_dict(orient='records')
        })
    
    except Exception as e:
        return jsonify(message=f"Error loading dataset: {str(e)}")

@app.route('/train', methods=['POST'])
def train():
    global dataset_path, model_results
    
    if not dataset_path:
        return jsonify(message="Upload a dataset first")
    
    try:
        # Load dataset
        df = pd.read_csv(dataset_path)
        
        # Get form data
        feature_columns = request.form.getlist('features[]')
        target_column = request.form['target']
        model_type = request.form['model']
        problem_type = request.form['problem_type']
        
        # Validate inputs
        if not feature_columns or not target_column:
            return jsonify(message="Please select features and target")
        
        # Separate features and target
        X = df[feature_columns]
        y = df[target_column]
        
        # Create preprocessing pipeline
       
        preprocessor = create_preprocessing_pipeline(df, feature_columns, target_column)
        
        # Determine model based on problem type
       def create_lstm_model(input_shape, output_units, problem_type):
            model = Sequential()
            model.add(LSTM(50, activation='relu', input_shape=input_shape))  # Adjust LSTM layer size as needed
            model.add(Dense(output_units, activation='sigmoid' if problem_type == 'classification' else 'linear'))
            model.compile(optimizer='adam', loss='binary_crossentropy' if problem_type == 'classification' else 'mean_squared_error', metrics=['accuracy'])
            return model
        
        # Reshape input for LSTM if necessary
        if model_type == 'LSTM':
            input_shape = (X.shape[1], 1)  # Assuming each feature is treated as a time step
            X = X.values.reshape((X.shape[0], X.shape[1], 1))
            if problem_type == 'classification':
                model = KerasClassifier(build_fn=create_lstm_model, input_shape=input_shape, output_units=1, problem_type=problem_type, epochs=10, batch_size=32)
            else:
                model = KerasRegressor(build_fn=create_lstm_model, input_shape=input_shape, output_units=1, problem_type=problem_type, epochs=10, batch_size=32)
        elif model_type == 'RandomForest':
            if problem_type == 'classification':
                model = RandomForestClassifier(random_state=42)
            else:
                model = RandomForestRegressor(random_state=42)
        elif model_type == 'LogisticRegression':
            model = LogisticRegression(max_iter=1000)
        else:
            model = LinearRegression()
        
        # Create full pipeline
        pipeline = Pipeline(steps=[
            ('preprocessor', preprocessor),
            ('model', model)
        ])
        
        
        # Split data
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
        
        # Train pipeline
        pipeline.fit(X_train, y_train)
        
        # Save model results
        model_results = {
            'pipeline': pipeline,
            'feature_columns': feature_columns,
            'target_column': target_column
        }
        
        return jsonify({
            'message': "Model trained successfully."
        })
    
    except Exception as e:
        import traceback
        traceback.print_exc()
        return jsonify(message=f"Training error: {str(e)}")

@app.route('/predict', methods=['POST'])
def predict():
    global model_results
    
    if not model_results:
        return jsonify(message="Train a model first")
    
    try:
        # Load dataset
        df = pd.read_csv(dataset_path)
        
        # Prepare data using saved pipeline
        pipeline = model_results['pipeline']
        X = df[model_results['feature_columns']]
        
        # Predict
        predictions = pipeline.predict(X)
        
        # Add predictions to dataframe
        df['Prediction'] = predictions
        
        return jsonify(df.to_dict(orient='records'))
    
    except Exception as e:
        return jsonify(message=f"Prediction error: {str(e)}")

@app.route('/technicien/machines', methods=['GET'])
def get_machine_predictions():
    global model_results
    
    try:
        if not model_results:
            return jsonify(message="No model trained yet"), 400
        
        # Load dataset
        df = pd.read_csv(dataset_path)
        
        # Prepare data using saved pipeline
        pipeline = model_results['pipeline']
        X = df[model_results['feature_columns']]
        
        # Predict
        predictions = pipeline.predict(X)
        
        # Add predictions to dataframe
        df['Prediction'] = predictions
        
        # Format the response to match the required structure
        response_data = df.to_dict(orient='records')
        
        return jsonify(response_data)
    
    except Exception as e:
        return jsonify(message=f"Error retrieving machine predictions: {str(e)}"), 500

@app.route('/technicien/schedule-maintenance/<machine_id>', methods=['POST'])
def schedule_maintenance(machine_id):
    try:
        return jsonify(message=f"Maintenance scheduled for {machine_id}")
    except Exception as e:
        return jsonify(message=f"Error scheduling maintenance: {str(e)}"), 500

if __name__ == '__main__':
    app.run(debug=True)