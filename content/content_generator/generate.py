import random

from builders import build_description
from config import (
    CUSTOMERS,
    DOCUMENTS_PER_CUSTOMER,
    OUTPUT_DIRECTORY,
    OUTPUT_FILENAME,
)
from customers import CUSTOMER_PROFILES
from exporter import export_json
from topics import TOPICS


FORMATS = [
    "video",
    "e-learning",
    "webinar",
    "blended"
]

DIFFICULTIES = [
    "beginner",
    "intermediate",
    "advanced"
]


def build_course(course_id, customer, topic):
    profile = CUSTOMER_PROFILES[customer]

    return {
        "id": course_id,
        "customer": customer,
        "title": f"{topic} – Modul {course_id}",
        "description": build_description(topic),
        "tags": [
            topic.lower(),
            profile["style"],
            "training",
            "praxis"
        ],
        "difficulty": random.choice(DIFFICULTIES),
        "duration_minutes": random.choice([20, 30, 45, 60, 90]),
        "format": random.choice(FORMATS)
    }


def main():
    random.seed(42)

    courses = []
    course_id = 1

    for customer in CUSTOMERS:
        for i in range(DOCUMENTS_PER_CUSTOMER):
            topic = TOPICS[i % len(TOPICS)]
            courses.append(build_course(course_id, customer, topic))
            course_id += 1

    export_json(courses, OUTPUT_DIRECTORY, OUTPUT_FILENAME)

    print(f"Created {len(courses)} courses.")


if __name__ == "__main__":
    main()