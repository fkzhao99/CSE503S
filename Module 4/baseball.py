import os
import sys
import re

if len(sys.argv) < 2:
    sys.exit(f"Usage: {sys.argv[0]} filename")

filename = sys.argv[1]

if not os.path.exists(filename):
    sys.exit(f"Error: File '{sys.argv[1]}' not found")


def getinfo(line, playerdict):
    res = re.match(r"\b(\w+ \w+) batted (\d+) times with (\d+) hits and \d+ runs\b", line)
    if res:
        name = res.group(1)
        times = int(res.group(2))
        hits = int(res.group(3))
        if name not in playerdict.keys():
            playerdict[name] = [times, hits]
        else:
            playerdict[name][0] += times
            playerdict[name][1] += hits


def getfinaldict(infodict):
    finaldict = []
    for key in infodict.keys():
        times = infodict[key][0]
        hits = infodict[key][1]
        finaldict.append([key, round(hits / times, 3)])
    return finaldict


infodict = {}
with open(filename) as f:
    for line in f:
        info = getinfo(line, infodict)

finaldict = getfinaldict(infodict)

finaldict = sorted(finaldict, key=lambda x: x[1], reverse=True)

for info in finaldict:
    print('{}: {:.3f}'.format(info[0], info[1]))
