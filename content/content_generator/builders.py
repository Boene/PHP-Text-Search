import random
from templates import INTRODUCTIONS, BODY, ENDINGS, LIST_SECTIONS
from vocabulary import TARGET_GROUPS

def build_description(topic: str) -> str:
    intro = random.choice(INTRODUCTIONS).format(topic=topic)
    body = random.choice(BODY)
    ending = random.choice(ENDINGS)
    section = random.choice(LIST_SECTIONS)

    lines = [
        intro,
        "",
        body,
        "",
        f"{section[0]}:",
        f"- {section[1]}",
        f"- {section[2]}",
        f"- {section[3]}",
        "",
        f"Zielgruppe: {random.choice(TARGET_GROUPS)}",
        ending
    ]
    return "\n".join(lines)