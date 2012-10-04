
/** classes indexes **/
db.getCollection("classes").ensureIndex({
  "_id": NumberInt(1)
},[
  
]);

/** students indexes **/
db.getCollection("students").ensureIndex({
  "_id": NumberInt(1)
},[
  
]);

/** teachers indexes **/
db.getCollection("teachers").ensureIndex({
  "_id": NumberInt(1)
},[
  
]);

/** classes records **/
db.getCollection("classes").insert({
  "_id": ObjectId("506b0e55e7a3c66b68000000"),
  "is_active": true,
  "paid": false,
  "students": [
    {
      "name": "Wayne Grover",
      "id": "506b67a5e7a3c63e9f000001",
      "payment": "cash",
      "use_card": false,
      "goods_display": false,
      "total": "12"
    },
    {
      "name": "Jimmy Jams",
      "id": "506b1defe7a3c6af72000000",
      "payment": "cash",
      "use_card": true,
      "goods_display": "Water ($2), ",
      "total": "2"
    },
    {
      "name": "Test Me",
      "id": "506e0581e7a3c638f9000000",
      "payment": "cash",
      "use_card": true,
      "goods_display": "Class Card - 5 classes ($20), ",
      "total": "20"
    }
  ],
  "teacher": "skoch",
  "timestamp": "1349193301058",
  "type": "yoga"
});
db.getCollection("classes").insert({
  "_id": ObjectId("506dd7e6e7a3c6e0ff000000"),
  "teacher": "nkoch",
  "type": "yoga",
  "paid": false,
  "is_active": false,
  "timestamp": "1349375974846"
});

/** students records **/
db.getCollection("students").insert({
  "_id": ObjectId("506b1defe7a3c6af72000000"),
  "card": 5,
  "classes_taken_with_card": 0,
  "fullname": "Jimmy Jams",
  "total_classes_taken": 0,
  "username": "jjams"
});
db.getCollection("students").insert({
  "_id": ObjectId("506b67a5e7a3c63e9f000001"),
  "card": 0,
  "classes_taken_with_card": 0,
  "fullname": "Wayne Grover",
  "total_classes_taken": 0,
  "username": "wgrover"
});
db.getCollection("students").insert({
  "_id": ObjectId("506c9057e7a3c60dcf000000"),
  "card": 0,
  "classes_taken_with_card": 0,
  "fullname": "Test User",
  "total_classes_taken": 0,
  "username": "tuser"
});
db.getCollection("students").insert({
  "_id": ObjectId("506e0581e7a3c638f9000000"),
  "card": NumberInt(5),
  "classes_taken_with_card": NumberInt(0),
  "fullname": "Test Me",
  "total_classes_taken": NumberInt(0),
  "username": "tme"
});

/** teachers records **/
db.getCollection("teachers").insert({
  "_id": ObjectId("506a2165e7a3c68646000000"),
  "is_admin": 1,
  "last_login": "1349375989020",
  "login": "skoch",
  "name": "Stephen Koch",
  "pwd": "qwe123;",
  "session_id": "34c31af07cce5c0552ee1930a965097b"
});
db.getCollection("teachers").insert({
  "_id": ObjectId("5069cf2ee7a3c6b21f000000"),
  "is_admin": 1,
  "last_login": "1349375971325",
  "login": "nkoch",
  "name": "Nikki Koch",
  "pwd": "qwe123;",
  "session_id": ""
});
