from flask import Flask, request, render_template, redirect, url_for, session, send_from_directory
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
from tensorflow.keras.applications.resnet50 import preprocess_input
from PIL import Image
import numpy as np
import os
import uuid

# -------------------- FLASK SETUP --------------------
app = Flask(
    __name__,
    template_folder='ladybugwebsite',
    static_folder='ladybugwebsite'
)

# Generate a secure secret key once (see below) and paste here
app.secret_key = 'a5d462c131adad256622517719a05213'

# -------------------- PATH SETUP --------------------
UPLOAD_FOLDER = os.path.join(app.static_folder, 'uploads')
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# -------------------- MODEL LOADING --------------------
model = load_model('model/model_vgg16.keras')
class_names = ['Benign', 'Malignant', 'Normal']  # Update as per training labels

# -------------------- IMAGE PREPROCESSING --------------------
def preprocess(img):
    img = img.resize((224, 224))  # ResNet standard
    img_array = image.img_to_array(img)
    img_array = preprocess_input(img_array)
    return np.expand_dims(img_array, axis=0)

# -------------------- ROUTES --------------------
@app.route('/')
def home():
    return render_template('upload.html')

@app.route('/predict', methods=['POST'])
def predict():
    try:
        file = request.files['image']
        img = Image.open(file.stream).convert('RGB')
        input_data = preprocess(img)

        # Make prediction
        preds = model.predict(input_data)[0]
        top_index = np.argmax(preds)
        label = class_names[top_index]
        confidence = float(preds[top_index])

        # Save image with a random name
        filename = f"{uuid.uuid4().hex}.png"
        filepath = os.path.join(UPLOAD_FOLDER, filename)
        img.save(filepath)

        # Store result in session
        session['prediction'] = label
        session['confidence'] = f"{confidence * 100:.2f}%"
        session['image_filename'] = filename

        return redirect(url_for('result'))

    except Exception as e:
        return f"Prediction failed: {str(e)}", 500

@app.route('/result')
def result():
    prediction = session.get('prediction')
    confidence = session.get('confidence')
    image_filename = session.get('image_filename')

    if not prediction or not image_filename:
        return redirect(url_for('home'))

    return render_template('result.html',
                           prediction=prediction,
                           confidence=confidence,
                           image_file=image_filename)

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(UPLOAD_FOLDER, filename)

@app.route('/upload')
def upload():
    return render_template('upload.html')

# -------------------- RUN --------------------
if __name__ == '__main__':
    app.run(debug=True)
