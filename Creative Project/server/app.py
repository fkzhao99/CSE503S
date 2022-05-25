from datetime import timedelta
from flask import Flask, jsonify, request
from flask_cors import CORS
import pymongo
from api.users import users
from api.cate import cates
from api.events import events
from flask_jwt_extended import JWTManager, get_jwt_identity, create_access_token, get_jwt, set_access_cookies
# configuration
DEBUG = True

# instantiate the app
app = Flask(__name__)
app.config.from_object(__name__)
app.register_blueprint(users)
app.register_blueprint(events)
app.register_blueprint(cates)
app.config['JWT_SECRET_KEY'] = "jwtsecret"
app.config["JWT_TOKEN_LOCATION"] = ["cookies"]
app.config["JWT_ACCESS_TOKEN_EXPIRES"] = timedelta(hours=24)


# # enable CORS
CORS(app)
jwt = JWTManager(app)

client = pymongo.MongoClient("mongodb+srv://Luka:root@creativeproject.uee95.mongodb.net/myFirstDatabase?retryWrites=true&w=majority")
db = client.Creative
userdb = db.user
eventdb = db.event
catedb = db.category


if __name__ == '__main__':
    app.run()