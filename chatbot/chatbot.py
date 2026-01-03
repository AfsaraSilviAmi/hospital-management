from flask import Flask, request, jsonify
from flask_cors import CORS
import json
from rapidfuzz import fuzz  # pip install rapidfuzz

app = Flask(__name__)
CORS(app)

# Load intents
with open('intents.json') as f:
    categorized_intents = json.load(f)

# Flatten intents for easier search
intents = []
for category, intents_list in categorized_intents.items():
    for intent in intents_list:
        intents.append(intent)

@app.route('/chat', methods=['POST'])
def chat():
    user_input = request.json.get('message', '').lower().strip()
    if not user_input:
        return jsonify({'response': "Please type a message."})

    best_intent = None
    highest_score = 0

    # Evaluate each intent
    for intent in intents:
        keywords = intent.get('keywords', [])
        intent_score = 0

        for keyword in keywords:
            similarity = fuzz.partial_ratio(keyword.lower(), user_input)
            # Add score for each keyword
            if similarity > 70:  # keyword considered matched if similarity > 70
                intent_score += similarity

        # Keep the intent with the highest total score
        if intent_score > highest_score:
            highest_score = intent_score
            best_intent = intent

    # Threshold to respond
    if best_intent and highest_score >= 70:
        return jsonify({'response': best_intent['answer']})
    else:
        return jsonify({'response': "I'm sorry, I don't understand that. Please contact support."})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)
