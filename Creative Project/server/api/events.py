from asyncio import events
from importlib.resources import contents
import json
import uuid
from flask import Blueprint, jsonify, render_template, abort, request
from jinja2 import TemplateNotFound
from flask_jwt_extended import create_access_token, create_refresh_token, set_access_cookies, set_refresh_cookies, \
    jwt_required, get_jwt_identity, unset_jwt_cookies

events = Blueprint('events', __name__)

@events.route('/getEvents', methods=['GET'])
@jwt_required()
def listEvents():
    objName = get_jwt_identity()
    from app import eventdb
    eventlist=[]
    for result in eventdb.find({'username': objName}):
        del result['_id'] 
        eventlist.append(result)
    return jsonify({
        "username": objName,
        "eventlist": eventlist,
        "res" : 1,
        "msg" : "get events!"
    })

@events.route('/addEvent', methods=['POST'])
@jwt_required()
def addEvent():
    objName = get_jwt_identity()
    from app import eventdb
    post_data = request.get_json()
    eventdb.insert_one({"content": post_data["content"], 'username': objName, "date": post_data["date"], "cate": post_data["cate"], "done": False, "uuid": str(uuid.uuid4())})
    return jsonify({
        "username": objName,
        "msg" : "Add event!",
        "res" : 1
    })

@events.route('/finishEvent', methods=['POST'])
@jwt_required()
def checkFinish():
    objName = get_jwt_identity()
    post_data = request.get_json()
    from app import eventdb
    eventdb.update_one({'uuid': post_data["uuid"]}, {'$set': {"done": post_data["isDone"]}})
    return jsonify({
        "username": objName,
        "res" : 1,
        "msg" : "get events!"
    })


@events.route('/editEvent', methods=['POST'])
@jwt_required()
def editEvent():
    objName = get_jwt_identity()
    from app import eventdb
    post_data = request.get_json()
    eventdb.update_one({'uuid': post_data["uuid"]}, {'$set': {"cate": post_data["cate"], "date": post_data["date"], "content": post_data["content"], "done": post_data["isDone"]}})
    return jsonify({
        "username": objName,
        "msg" : "Edit event!",
        "res" : 1
    })

@events.route('/delEvent', methods=['POST'])
@jwt_required()
def delEvent():
    objName = get_jwt_identity()
    from app import eventdb
    post_data = request.get_json()
    eventdb.delete_one({'uuid': post_data["uuid"]})
    return jsonify({
        "username": objName,
        "msg" : "Edit event!",
        "res" : 1
    })