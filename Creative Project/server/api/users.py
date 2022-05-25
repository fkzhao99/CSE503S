import json
from bson import ObjectId
from flask import Blueprint, jsonify, render_template, abort, request
from jinja2 import TemplateNotFound
from werkzeug.security import generate_password_hash, check_password_hash
from flask_jwt_extended import create_access_token, create_refresh_token, set_access_cookies, set_refresh_cookies, \
    jwt_required, get_jwt_identity, unset_jwt_cookies


users = Blueprint('users', __name__)


@users.route('/signUp', methods=['POST'])
def respSignUp():
    from app import userdb, catedb
    post_data = request.get_json()
    flag = 0
    msg = ''
    passwdtoken = generate_password_hash(post_data['password'])
    if userdb.find_one({'username': post_data["username"]}) != None:
        msg = 'User name already exists!'
    else:
        userdb.insert_one({'username': post_data["username"], 'passwd': passwdtoken})
        msg = 'Sign up successfully!'
        flag = 1
    print(msg)
    return jsonify({
        "username": post_data["username"],
        "res" : flag,
        "msg" : msg
    })

@users.route('/signIn', methods=['POST'])
def logIn():
    from app import userdb
    post_data = request.get_json()
    flag = 0
    msg = ''

    if userdb.find_one({'username': post_data["username"]}) == None:
       pass
    elif check_password_hash(userdb.find_one({'username': post_data["username"]})["passwd"], post_data['password']):
        resp = jsonify({
            "username": post_data["username"],
            "msg" : '',
            "res" : 1
        })
        msg = 'Log in successfully!'
        uname = str(post_data["username"])
         # auth token
        access_token = create_access_token(identity=uname)
        refresh_token = create_refresh_token(identity=uname)
        set_access_cookies(resp, access_token)
        set_refresh_cookies(resp, refresh_token)
        print(access_token)
        return resp
    else:
        msg = 'Wrong password!'
    print(msg)
    return jsonify({
        "username": post_data["username"],
        "res" : flag,
        "msg" : msg
    })


@users.route("/logOut", methods=["GET"])
def logout():
    resp = jsonify({'logout': True})
    unset_jwt_cookies(resp)
    return resp

@users.route("/refresh", methods=["POST"])
@jwt_required(refresh=True)
def refresh():
    # Create the new access token
    current_user = get_jwt_identity()
    access_token = create_access_token(identity=current_user)
    # Set the JWT access cookie in the response
    resp = jsonify({'refresh': True})
    set_access_cookies(resp, access_token)
    return resp

@users.route('/checkLogin', methods=["GET"])
@jwt_required()
def getInfo():
    objName = get_jwt_identity()
    return jsonify({
    "username": objName,
    "msg" : "There is an user"
    })


@users.route('/editProfile', methods=["POST"])
@jwt_required()
def editProfile():
    from app import userdb, catedb, eventdb
    post_data = request.get_json()
    objName = get_jwt_identity()
    res=0
    msg=''
    if check_password_hash(userdb.find_one({'username': objName})["passwd"], post_data['oldPassword']):
        passwdtoken = generate_password_hash(post_data['newPassword'])
        userdb.update_one({'username': objName,}, {'$set': {'username': post_data["username"], "passwd":passwdtoken}})
        eventdb.update_many({'username': objName}, {'$set': {'username': post_data["username"]}})
        catedb.update_many({'username': objName}, {'$set': {'username': post_data["username"]}})
        res=1
    else:
        msg="Wrong password!"
    return jsonify({
    "username": objName,
    "msg" : msg,
    "res": res
    })